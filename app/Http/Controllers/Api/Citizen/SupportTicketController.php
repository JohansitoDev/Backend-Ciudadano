<?php

namespace App\Http\Controllers\Api\Citizen;

use App\Http\Controllers\Controller;
use App\Models\TicketsSoporte;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    public function index(Request $request)
    {
        $tickets = $request->user()->ticketsSoporte()
                           ->orderBy('creado_en', 'desc')
                           ->get();

        return response()->json([
            'tickets_soporte' => $tickets,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'asunto' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'categoria' => 'required|string|max:50',
            'prioridad' => 'required|string|in:baja,media,alta,urgente',
        ]);

        $ticket = TicketsSoporte::create([
            'ciudadano_id' => $request->user()->id, 
            'asunto' => $request->asunto,
            'descripcion' => $request->descripcion,
            'categoria' => $request->categoria,
            'prioridad' => $request->prioridad,
            'estado' => 'abierto',
        ]);

        return response()->json([
            'message' => 'Ticket de soporte creado exitosamente.',
            'ticket_soporte' => $ticket,
        ], 201);
    }

    public function storeGuest(Request $request)
    {
        $request->validate([
            'asunto' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'categoria' => 'required|string|max:50',
            'prioridad' => 'required|string|in:baja,media,alta,urgente',
            'correo_invitado' => 'required|email|max:255',
            'telefono_invitado' => 'nullable|string|max:20',
        ]);

        $ticket = TicketsSoporte::create([
            'ciudadano_id' => null,
            'correo_invitado' => $request->correo_invitado,
            'telefono_invitado' => $request->telefono_invitado,
            'asunto' => $request->asunto,
            'descripcion' => $request->descripcion,
            'categoria' => $request->categoria,
            'prioridad' => $request->prioridad,
            'estado' => 'abierto',
        ]);

        return response()->json([
            'message' => 'Ticket de soporte para invitado creado exitosamente.',
            'ticket_soporte' => $ticket,
        ], 201);
    }

    public function show(TicketsSoporte $ticketSoporte)
    {
    
        if ($ticketSoporte->ciudadano_id && $ticketSoporte->ciudadano_id !== request()->user()->id) {
            abort(403, 'No tienes permiso para ver este ticket.');
        }

      
        if (is_null($ticketSoporte->ciudadano_id) && !request()->user()) { 
           
        }


        return response()->json([
            'ticket_soporte' => $ticketSoporte,
        ]);
    }

  
    public function update(Request $request, TicketsSoporte $ticketSoporte) {
        abort(405, 'Método no permitido para ciudadanos en este recurso.');
    }

    public function destroy(TicketsSoporte $ticketSoporte) {
        abort(405, 'Método no permitido para ciudadanos en este recurso.');
    }
}

