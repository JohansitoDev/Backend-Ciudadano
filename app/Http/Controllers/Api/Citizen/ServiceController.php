<?php

namespace App\Http\Controllers\Api\Citizen;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function indexPublic()
    {
        $servicios = Servicio::where('esta_activo', true)
                               ->with('institucion')
                               ->get();

        return response()->json([
            'servicios' => $servicios,
        ]);
    }
}