<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function mostrarLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('usuario', 'contrasenia');

        if (Auth::attempt([
            'usuario' => $credentials['usuario'],
            'password' => $credentials['contrasenia']
        ])) {
            return redirect()->intended('/admin');
        }

        return back()->withErrors(['usuario' => 'Usuario o contraseña incorrectos.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }



    public function mostrarRegistro()
{
    return view('auth.registro');
}

public function registro(Request $request)
{
    $request->validate([
        'nombres' => 'required|string|max:100',
        'apellidos' => 'required|string|max:100',
        'correo' => 'required|email|unique:usuarios,correo',
        'contrasenia' => 'required|string|min:8'
    ]);

    // Crear usuario
    $usuario = Usuario::create([
        'nombres' => $request->nombres,
        'apellidos' => $request->apellidos,
        'correo' => $request->correo,
        'usuario' => $request->usuario,
        'contrasenia' => $request->contrasenia, // La contraseña se encriptará automáticamente por el mutador
        'rol' => $request->rol,
        'telefono' => $request->telefono,
    ]);

   // Auth::login($usuario); // Inicia sesión automáticamente
 
    return redirect('/login')->with('success', 'Registro exitoso. Por favor, inicia sesión.');
}

}
