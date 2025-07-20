<?php

namespace App\Http\Controllers\Api\Citizen;

use App\Http\Controllers\Controller;
use App\Models\PuntoGob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GovernmentPointController extends Controller
{
    public function indexPublic()
    {
        $puntosGob = PuntoGob::where('esta_activo', true)->get([
            'id', 'nombre', 'direccion', 'latitud', 'longitud', 'telefono', 'correo_electronico'
        ]);

        return response()->json([
            'puntos_gob' => $puntosGob,
        ]);
    }

    public function nearest(Request $request)
    {
        $request->validate([
            'current_latitude' => 'required|numeric',
            'current_longitude' => 'required|numeric',
            'mode' => 'nullable|string|in:driving,walking,bicycling,transit',
        ]);

        $currentLatitude = $request->current_latitude;
        $currentLongitude = $request->current_longitude;
        $mode = $request->mode ?? 'driving';

        $puntosGob = PuntoGob::where('esta_activo', true)->get();

        if ($puntosGob->isEmpty()) {
            return response()->json(['message' => 'No hay Puntos GOB activos disponibles.'], 404);
        }

        $destinations = $puntosGob->map(function ($punto) {
            return $punto->latitud . ',' . $punto->longitud;
        })->implode('|');

        $googleMapsApiKey = env('Maps_API_KEY');

        if (empty($googleMapsApiKey)) {
            return response()->json(['message' => 'La clave API de Google Maps no está configurada.'], 500);
        }

        try {
            $response = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json', [
                'origins' => "{$currentLatitude},{$currentLongitude}",
                'destinations' => $destinations,
                'mode' => $mode,
                'key' => $googleMapsApiKey,
                'units' => 'metric',
            ])->json();

            $results = [];
            $closestPuntoGobId = null; // Cambiado a ID para mejor referencia
            $minDuration = PHP_INT_MAX;

            if (isset($response['rows'][0]['elements'])) {
                foreach ($response['rows'][0]['elements'] as $index => $element) {
                    $punto = $puntosGob[$index];
                    $distanceText = $element['distance']['text'] ?? 'N/A';
                    $durationText = $element['duration']['text'] ?? 'N/A';
                    $durationValue = $element['duration']['value'] ?? PHP_INT_MAX;

                    if ($durationValue < $minDuration) {
                        $minDuration = $durationValue;
                        $closestPuntoGobId = $punto->id;
                    }

                    $results[] = [
                        'id' => $punto->id,
                        'nombre' => $punto->nombre,
                        'direccion' => $punto->direccion,
                        'latitud' => $punto->latitud,
                        'longitud' => $punto->longitud,
                        'distancia_texto' => $distanceText,
                        'duracion_texto' => $durationText,
                        'duracion_segundos' => $durationValue,
                    ];
                }
            }

            $results = collect($results)->map(function ($item) use ($closestPuntoGobId) {
                $item['es_mas_cercano'] = ($item['id'] === $closestPuntoGobId);
                return $item;
            })->sortBy('duracion_segundos')->values()->toArray();

            return response()->json([
                'mensaje' => 'Puntos GOB cercanos y tiempos de viaje.',
                'ubicacion_actual' => [
                    'latitud' => $currentLatitude,
                    'longitud' => $currentLongitude,
                ],
                'puntos_gob' => $results,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al calcular distancias.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function showServices(PuntoGob $puntoGob)
    {
        $servicios = $puntoGob->servicios()->where('esta_activo', true)->get();

        return response()->json([
            'punto_gob' => $puntoGob->only(['id', 'nombre', 'direccion']),
            'servicios_disponibles' => $servicios->map(function($servicio){
                return $servicio->only(['id', 'nombre', 'descripcion', 'duracion_minutos']);
            }),
        ]);
    }
}