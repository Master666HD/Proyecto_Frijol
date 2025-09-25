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
    // 🔹 Log del payload recibido desde la app
    \Log::info('🔹 Login request recibido', [
        'payload' => $request->all()
    ]);

    $request->validate([
        'userName' => 'required',
        'password' => 'required'
    ]);

    // 🔹 Intentamos buscar al usuario
    $usuario = Usuario::where('usuario', $request->userName)->first();

    if (!$usuario) {
        \Log::warning('⚠️ Login fallido: usuario no encontrado', [
            'userName_input' => $request->userName
        ]);
        return response()->json(['message' => 'Credenciales inválidas'], 401);
    }

    // 🔹 Revisamos contraseña
    if (!Hash::check($request->password, $usuario->contrasenia)) {
        \Log::warning('⚠️ Login fallido: contraseña incorrecta', [
            'user_id'        => $usuario->id,
            'password_input' => $request->password,
            'stored_hash'    => $usuario->contrasenia,
        ]);
        return response()->json(['message' => 'Credenciales inválidas'], 401);
    }

    // 🔹 Revisamos rol
    if ($usuario->rol !== 'Agricultor') {
        \Log::warning('⚠️ Acceso denegado por rol', [
            'user_id' => $usuario->id,
            'rol_actual' => $usuario->rol
        ]);
        return response()->json(['message' => 'Acceso denegado'], 403);
    }

    // 🔹 Borrar tokens viejos
    $usuario->tokens()->delete();

    // 🔹 Crear token nuevo
    $token = $usuario->createToken('auth_token')->plainTextToken;

    // 🔹 LOG final antes de responder
    \Log::info('✅ Login exitoso', [
        'user'  => $usuario->toEnglishResponse(),
        'token' => $token
    ]);

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
   public function update(Request $request)
{
    \Log::info('Entrando a AuthController@update', [
        'request' => $request->all(),
        'user_id' => optional($request->user())->id,
    ]);

    $userId = $request->user()?->id ?? 0;

    $request->validate([
        'userName' => 'required|string|max:255|unique:usuarios,usuario,' . $userId . ',id',
    ]);

    $user = $request->user();

    \Log::info('Usuario antes de actualizar', [
        'id' => $user->id,
        'usuario' => $user->usuario,
    ]);

    $user->usuario = $request->userName;
    $user->save();

    \Log::info('Usuario actualizado', [
        'id' => $user->id,
        'usuario' => $user->usuario,
    ]);

    return response()->json([
        'message' => 'Nombre de usuario actualizado correctamente',
        'user'    => $user->toEnglishResponse()
    ], 200);
}

public function updatePassword(Request $request) 
{
    \Log::info('🔹 Entrando a AuthController@updatePassword', [
        'user_id' => optional($request->user())->id,
        'payload' => $request->all(), // 👈 log de lo que viene de la app
    ]);

    $request->validate([
        'currentPassword' => 'required|string',
        'newPassword'     => 'required|string|min:6|confirmed', 
        // en el JSON debe venir: newPassword y newPassword_confirmation
    ]);

    $user = $request->user();

    // Verificamos la contraseña actual
    if (!\Hash::check($request->currentPassword, $user->contrasenia)) {
        \Log::warning('⚠️ Contraseña actual incorrecta', [
            'user_id' => $user->id,
            'input_password' => $request->currentPassword,
            'stored_hash' => $user->contrasenia,
        ]);

        return response()->json([
            'message' => 'La contraseña actual es incorrecta'
        ], 403);
    }

    
    $user->contrasenia = $request->newPassword;
    $user->save();

    \Log::info('✅ Contraseña actualizada correctamente', [
        'user_id' => $user->id,
        'new_hash' => $user->contrasenia,
    ]);

    return response()->json([
        'message' => 'Contraseña actualizada correctamente'
    ], 200);
}

}


