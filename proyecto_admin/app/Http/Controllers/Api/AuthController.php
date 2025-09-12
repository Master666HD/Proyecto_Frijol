<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{    public function register(Request $request)
    {
        $request->validate([
            'firstName'   => 'required',
            'lastName'    => 'required',
            'phoneNumber' => 'required|numeric|digits:8',
            'email'       => 'required|email|unique:usuarios,correo',
            'userName'    => 'required|unique:usuarios,usuario',
            'password'    => 'required|min:6',
            'role'        => 'required'
        ]);

        try {
            $user = Usuario::create([
                'nombres'    => $request->firstName,
                'apellidos'  => $request->lastName,
                'telefono'   => $request->phoneNumber,
                'correo'     => $request->email,
                'usuario'    => $request->userName,
                'contrasenia'=> $request->password, // asegúrate de usar mutator con bcrypt
                'rol'        => $request->role,
                'estado'     => true
            ]);

            return response()->json([
                'message' => 'Usuario registrado con éxito.',
                'user'    => $user->toEnglishResponse()
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Error al registrar el usuario.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'userName' => 'required',
            'password' => 'required'
        ]);

        $usuario = Usuario::where('usuario', $request->userName)->first();

        if (!$usuario || !Hash::check($request->password, $usuario->contrasenia)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        if ($usuario->rol !== 'Agricultor') {
            return response()->json(['message' => 'Acceso denegado'], 403);
        }

        // 🔹 Borrar tokens viejos para evitar acumulación
        $usuario->tokens()->delete();

        // 🔹 Crear token nuevo
        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login exitoso',
            'user'    => $usuario->toEnglishResponse(),
            'token'   => $token
        ]);
    }

    public function logout(Request $request)
    {
        // Revoca solo el token en uso
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente'
        ]);
    }

    public function logoutAll(Request $request)
    {
        // Revoca todos los tokens del usuario (opcional)
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Sesión cerrada en todos los dispositivos'
        ]);
    }
}


