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

    // Validación en español pero usando campos en inglés
    $request->validate([
        'firstName' => 'required',
        'lastName' => 'required',
        'phoneNumber' => 'required|numeric|digits:8',
        'email' => 'required|email|unique:usuarios,correo',
        'userName' => 'required|unique:usuarios,usuario',
        'password' => 'required|min:6',
        'role' => 'required'
    ], [
        'firstName.required' => 'El nombre es obligatorio.',
        'lastName.required' => 'El apellido es obligatorio.',
        'phoneNumber.required' => 'El teléfono es obligatorio.',
        'phoneNumber.digits' => 'El teléfono debe tener exactamente 8 dígitos.',
        'phoneNumber.numeric' => 'Deben ser solo números.',
        'email.required' => 'El correo es obligatorio.',
        'email.email' => 'El correo debe ser válido.',
        'email.unique' => 'El correo ya está registrado.',
        'userName.required' => 'El nombre de usuario es obligatorio.',
        'userName.unique' => 'El nombre de usuario ya está en uso.',
        'password.required' => 'La contraseña es obligatoria.',
        'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        'role.required' => 'El rol es obligatorio.'
    ]);

    try {
        // Mapeo de campos en inglés a BD en español
        $user = Usuario::create([
            'nombres' => $request->input('firstName'),
            'apellidos' => $request->input('lastName'),
            'telefono' => $request->input('phoneNumber'),
            'correo' => $request->input('email'),
            'usuario' => $request->input('userName'),
            'contrasenia' => $request->input('password'), // hash automático en el setter
            'rol' => $request->input('role'),
            'estado' => true
        ]);

        return response()->json([
            'message' => 'Usuario registrado con éxito.',
            'user' => $user->toEnglishResponse()
        ], 201);

    } catch (\Exception $e) {
        \Log::error('Error al registrar usuario: ' . $e->getMessage());
        return response()->json([
            'error' => 'Error al registrar el usuario.',
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

    // Buscar usuario por nombre de usuario
    $usuario = Usuario::where('usuario', $request->userName)->first();

    // Verificar credenciales
    if (!$usuario || !Hash::check($request->password, $usuario->contrasenia)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    // Verificar rol
    if ($usuario->rol !== 'Agricultor') { // Ajusta el nombre del campo según tu tabla
        return response()->json(['message' => 'Access denied: only agriculturists allowed'], 403);
    }

    // Generar token
    $token = $usuario->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'Login successful',
        'user' => $usuario->toEnglishResponse(),
        'token' => $token
    ]);
}

}
