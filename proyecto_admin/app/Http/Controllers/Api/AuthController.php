<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{   public function register(Request $request)
{
    $messages = [
        'firstName.required'   => 'Debes ingresar tu nombre.',
        'lastName.required'    => 'Debes ingresar tu apellido.',
        'phoneNumber.required' => 'Debes ingresar tu número de teléfono.',
        'phoneNumber.numeric'  => 'El número de teléfono debe contener solo números.',
        'phoneNumber.digits'   => 'El número de teléfono debe tener 8 dígitos.',
        'email.required'       => 'Debes ingresar un correo electrónico.',
        'email.email'          => 'El correo electrónico no es válido.',
        'email.unique'         => 'Este correo ya está registrado.',
        'userName.required'    => 'Debes ingresar un nombre de usuario.',
        'userName.unique'      => 'El nombre de usuario ya existe. Intenta con otro.',
        'password.required'    => 'Debes ingresar una contraseña.',
        'password.min'         => 'La contraseña debe tener al menos 6 caracteres.',
        'role.required'        => 'Debes seleccionar un rol.',
    ];

    $request->validate([
        'firstName'   => 'required',
        'lastName'    => 'required',
        'phoneNumber' => 'required|numeric|digits:8',
        'email'       => 'required|email|unique:usuarios,correo',
        'userName'    => 'required|unique:usuarios,usuario',
        'password'    => 'required|min:6',
        'role'        => 'required'
    ], $messages);

    try {
        $user = Usuario::create([
            'nombres'     => $request->firstName,
            'apellidos'   => $request->lastName,
            'telefono'    => $request->phoneNumber,
            'correo'      => $request->email,
            'usuario'     => $request->userName,
            'contrasenia' => $request->password, // recuerda usar mutator con bcrypt
            'rol'         => $request->role,
            'estado'      => true
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Usuario registrado con éxito.',
            'user'    => $user->toEnglishResponse()
        ], 201);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al registrar el usuario. Intenta más tarde.',
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
        return response()->json([
        'success' => false,
        'code'    => 'INVALID_CREDENTIALS',
        'message' => 'Usuario o contraseña incorrectos'
    ], 401);
    }

    // 🔹 Revisamos contraseña
    if (!Hash::check($request->password, $usuario->contrasenia)) {
        \Log::warning('⚠️ Login fallido: contraseña incorrecta', [
            'user_id'        => $usuario->id,
            'password_input' => $request->password,
            'stored_hash'    => $usuario->contrasenia,
        ]);
      return response()->json([
        'success' => false,
        'code'    => 'INVALID_CREDENTIALS',
        'message' => 'Usuario o contraseña incorrectos'
    ], 401);
    }

    // 🔹 Revisamos rol
    if ($usuario->rol !== 'Agricultor') {
        \Log::warning('⚠️ Acceso denegado por rol', [
            'user_id' => $usuario->id,
            'rol_actual' => $usuario->rol
        ]);
      return response()->json([
        'success' => false,
        'code'    => 'INVALID_CREDENTIALS',
        'message' => 'Usuario o contraseña incorrectos'
    ], 401);
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
    try {
        \Log::info('Entrando a AuthController@update', [
            'request' => $request->all(),
            'user_id' => optional($request->user())->id,
        ]);

        $userId = $request->user()?->id ?? 0;

        // ✅ Validación con mensajes personalizados
        $validator = \Validator::make(
            $request->all(),
            [
                'userName' => 'required|string|max:255|unique:usuarios,usuario,' . $userId . ',id',
            ],
            [
                'userName.required' => 'Debes ingresar un nombre de usuario.',
                'userName.max' => 'El nombre de usuario no puede superar los 255 caracteres.',
                'userName.unique' => 'El nombre de usuario ya existe. Intenta con otro.',
            ]
        );

        if ($validator->fails()) {
            $errorText = $validator->errors()->first('userName') ?? 'Error de validación.';

            \Log::warning('Validación fallida al actualizar usuario', [
                'user_id' => $userId,
                'error' => $errorText,
            ]);

            return $this->jsonResponse(false, $errorText, null, 422);
        }

        $user = $request->user();

        if (!$user) {
            \Log::warning('Intento de actualización sin usuario autenticado');
            return $this->jsonResponse(false, 'No se encontró el usuario autenticado.', null, 401);
        }

        // ✅ Actualización
        $user->usuario = $request->userName;
        $user->save();

        \Log::info('Usuario actualizado correctamente', [
            'id' => $user->id,
            'usuario' => $user->usuario,
        ]);

        return $this->jsonResponse(true, 'Nombre de usuario actualizado correctamente.', $user, 200);

    } catch (\Throwable $e) {
        \Log::error('Excepción en AuthController@update', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return $this->jsonResponse(false, 'Ocurrió un error en el servidor. Intenta más tarde.', null, 500);
    }
}

/**
 * Helper para respuestas JSON limpias y consistentes
 */
private function jsonResponse($success, $message, $user = null, $status = 200)
{
    $response = [
        'success' => $success,
        'message' => $message,
    ];

    if ($user) {
        $response['user'] = $user->toEnglishResponse();
    }

    return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE);
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


