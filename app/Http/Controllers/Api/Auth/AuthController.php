<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Ciudadano;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'correo_electronico' => 'required|string|email|max:255|unique:usuarios,correo_electronico',
            'password' => 'required|string|min:8|confirmed',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:500',
        ]);

        $ciudadano = Ciudadano::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'correo_electronico' => $request->correo_electronico,
            'contrasena' => Hash::make($request->password),
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'estado' => 'activo',
            'correo_verificado_en' => now(),
        ]);

        $token = $ciudadano->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registro exitoso.',
            'ciudadano' => $ciudadano,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'correo_electronico' => 'required|email',
            'password' => 'required',
        ]);

        $ciudadano = Ciudadano::where('correo_electronico', $request->correo_electronico)->first();

        if (!$ciudadano || !Hash::check($request->password, $ciudadano->contrasena)) {
            throw ValidationException::withMessages([
                'correo_electronico' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        $ciudadano->tokens()->delete();
        $token = $ciudadano->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Inicio de sesión exitoso.',
            'ciudadano' => $ciudadano,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sesión cerrada exitosamente.']);
    }
}