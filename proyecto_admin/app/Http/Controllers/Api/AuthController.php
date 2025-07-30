<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        \Log::info('Datos recibidos en backend:', $request->all());
        $request->validate([
            'nombres' => 'required',
            'apellidos' => 'required',
            'telefono' => 'required|numeric|digits:8',
            'correo' => 'required|email|unique:usuarios,correo',
            'usuario' => 'required|unique:usuarios,usuario',
            'contrasenia' => 'required|min:6',
            'rol' => 'required'
        ], [
            'nombres.required' => 'El nombre es obligatorio.',
            'apellidos.required' => 'El apellido es obligatorio.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.digits' => 'El teléfono debe tener exactamente 8 dígitos.',
            'telefono.numeric' => 'Deben ser Solo numeros.',
            'correo.required' => 'El correo es obligatorio.',
            'correo.email' => 'El correo debe ser válido.',
            'correo.unique' => 'El correo ya está registrado.',
            'usuario.required' => 'El nombre de usuario es obligatorio.',
            'usuario.unique' => 'El nombre de usuario ya está en uso.',
            'contrasenia.required' => 'La contraseña es obligatoria.',
            'contrasenia.min' => 'La contraseña debe tener al menos 6 caracteres.',
            
        ]);

        try {
            $usuario = Usuario::create([
                'nombres' => $request->nombres,
                'apellidos' => $request->apellidos,
                'telefono' => $request->telefono,
                'correo' => $request->correo,
                'usuario' => $request->usuario,
                'contrasenia' => Hash::make($request->contrasenia),
                'rol' => $request->rol,
                'estado' => true
            ]);
        
            return response()->json([
                'mensaje' => 'Usuario registrado con éxito',
                'usuario' => [
                    'id' => $usuario->id,
                    'nombres' => $usuario->nombres,
                    'apellidos' => $usuario->apellidos,
                    'correo' => $usuario->correo,
                    'usuario' => $usuario->usuario,
                    'rol' => $usuario->rol
                ]
            ], 201);  // 201 indica que el recurso fue creado exitosamente
        } catch (\Exception $e) {
            // Respuesta en caso de error
            return response()->json([
                'error' => 'Error al registrar el usuario',
                'details' => $e->getMessage()
            ], 500);  // 500 indica un error en el servidor
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'usuario' => 'required',
            'contrasenia' => 'required',
        ]);

        $usuario = Usuario  ::where('usuario', $request->usuario)->first();

        if (!$usuario || !Hash::check($request->contrasenia, $usuario->contrasenia)) {
            return response()->json(['mensaje' => 'Credenciales inválidas'], 401);
        }

        if (!$usuario->estado) {
            return response()->json(['mensaje' => 'Usuario inactivo'], 403);
        }

       $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
        'mensaje' => 'Inicio de sesión exitoso',
        'usuario' => [
        'id' => $usuario->id,
        'nombre' => $usuario->nombres,
        'rol' => $usuario->rol
        ],
        'token' => $token
        ]);
    }
}
