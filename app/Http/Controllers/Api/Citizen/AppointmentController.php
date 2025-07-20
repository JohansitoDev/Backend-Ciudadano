<?php

namespace App\Http\Controllers\Api\Citizen;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Servicio;
use App\Models\PuntoGob; // Asegúrate de importar PuntoGob
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $citas = $request->user()->citas()
                         ->with(['servicio', 'puntoGob'])
                         ->orderBy('fecha_cita', 'desc')
                         ->orderBy('hora_cita', 'desc')
                         ->get();

        return response()->json([
            'citas' => $citas,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'servicio_id' => 'required|exists:servicios,id',
            'fecha_cita' => 'required|date|after_or_equal:today',
            'hora_cita' => 'required|date_format:H:i',
            'notas_ciudadano' => 'nullable|string|max:500',
            'punto_gob_id' => 'nullable|exists:puntos_gob,id', // Opcional, pero si se envía debe existir
        ]);

        $servicio = Servicio::find($request->servicio_id);
        if (!$servicio || !$servicio->esta_activo) {
            throw ValidationException::withMessages(['servicio_id' => 'El servicio seleccionado no es válido o no está activo.']);
        }

        // --- Lógica de Disponibilidad (EJEMPLO, debe ser más robusta) ---
        // Aquí podrías agregar lógica para:
        // 1. Validar que el servicio se ofrezca en el punto_gob_id si se especificó.
        //    (e.g., $puntoGob->servicios->contains($servicio->id))
        // 2. Verificar que no haya solapamientos en la agenda del PuntoGob para esa fecha y hora,
        //    considerando la duracion_minutos del servicio.
        //    Esto es complejo y requiere un sistema de calendario de disponibilidad.
        // --- FIN Lógica de Disponibilidad ---

        $qrData = Str::uuid()->toString();

        $cita = Cita::create([
            'ciudadano_id' => $request->user()->id, // Usamos ciudadano_id aquí
            'servicio_id' => $request->servicio_id,
            'fecha_cita' => $request->fecha_cita,
            'hora_cita' => $request->hora_cita,
            'estado' => 'pendiente',
            'notas_ciudadano' => $request->notas_ciudadano,
            'datos_qr' => $qrData,
            'punto_gob_id' => $request->punto_gob_id,
            'asignado_en' => null, // Se asigna cuando es escaneada
        ]);

        return response()->json([
            'message' => 'Cita agendada exitosamente.',
            'cita' => $cita->load(['servicio', 'puntoGob']),
        ], 201);
    }

    public function show(Cita $cita)
    {
        if ($cita->ciudadano_id !== request()->user()->id) { // Usamos ciudadano_id aquí
            abort(403, 'No tienes permiso para ver esta cita.');
        }

        return response()->json([
            'cita' => $cita->load(['servicio', 'puntoGob']),
        ]);
    }

    public function cancel(Cita $cita)
    {
        if ($cita->ciudadano_id !== request()->user()->id) { // Usamos ciudadano_id aquí
            abort(403, 'No tienes permiso para cancelar esta cita.');
        }

        if (!in_array($cita->estado, ['pendiente', 'confirmada'])) {
            throw ValidationException::withMessages(['estado' => 'Esta cita no puede ser cancelada en su estado actual.']);
        }

        $cita->estado = 'cancelada';
        $cita->save();

        return response()->json([
            'message' => 'Cita cancelada exitosamente.',
            'cita' => $cita->load(['servicio', 'puntoGob']),
        ]);
    }

    // Los métodos 'update' y 'destroy' del AppointmentController (resource) no se usarían
    // directamente por el ciudadano en este caso, por lo que puedes dejarlos vacíos
    // o eliminarlos si usas rutas explícitas en vez de Route::apiResource.
    public function update(Request $request, Cita $cita) {
        // Implementar solo si el ciudadano puede "modificar" la cita (ej. cambiar hora, servicio).
        // Sería muy similar a 'store' pero validando que sea SU cita y su estado permita el cambio.
        abort(405, 'Método no permitido para ciudadanos en este recurso.');
    }

    public function destroy(Cita $cita) {
        // El ciudadano solo debería "cancelar", no eliminar registros definitivamente.
        abort(405, 'Método no permitido para ciudadanos en este recurso.');
    }
}