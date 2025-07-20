<?php

namespace App\Http\Controllers\Api\Citizen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return response()->json([
            'ciudadano' => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        $ciudadano = $request->user();

        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:500',
            'correo_electronico' => 'required|string|email|max:255|unique:usuarios,correo_electronico,' . $ciudadano->id, 
        ]);

        $ciudadano->update($request->all());

        return response()->json([
            'message' => 'Perfil actualizado exitosamente.',
            'ciudadano' => $ciudadano,
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $ciudadano = $request->user();

        if (!Hash::check($request->current_password, $ciudadano->contrasena)) { 
            throw ValidationException::withMessages([
                'current_password' => ['La contraseña actual es incorrecta.'],
            ]);
        }

        $ciudadano->contrasena = Hash::make($request->new_contraseña); 
        $ciudadano->save();

        $ciudadano->tokens()->delete();

        return response()->json(['message' => 'Contraseña actualizada exitosamente. Por favor, inicia sesión de nuevo.']);
    }
}