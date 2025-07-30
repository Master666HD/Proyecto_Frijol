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
        $usuario = Usuario::where('usuario', $request->usuario)->first();
        if ($usuario && \Illuminate\Support\Facades\Hash::check($request->contrasenia, $usuario->contrasenia)) {
            Auth::login($usuario);
            $request->session()->regenerate();
            if ($usuario->rol === 'Admin') {
                return redirect()->intended('/admin');
            } else {
                return redirect()->intended('/login')->withErrors(['usuario' => 'No tienes los permisos para acceder']);
            }
        }
        return back()->withErrors(['usuario' => 'Credenciales inválidas'])->withInput();
    }
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'usuario' => 'required|unique:usuarios,usuario',
            'correo' => 'required|email|unique:usuarios,correo',
            'contrasenia' => 'required|min:8',
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'telefono' => 'required|numeric|digits:8',
            'rol' => 'required|string|max:255',
        ], [
            'nombres.required' => 'El nombre es obligatorio.',
            'apellidos.required' => 'El apellido es obligatorio.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.digits' => 'El teléfono debe tener exactamente 8 dígitos.',
            'telefono.numeric' => 'Deben ser solo números.',
            'correo.required' => 'El correo es obligatorio.',
            'correo.email' => 'El correo debe ser válido.',
            'correo.unique' => 'El correo ya está registrado.',
            'usuario.required' => 'El nombre de usuario es obligatorio.',
            'usuario.unique' => 'El nombre de usuario ya está en uso.',
            'contrasenia.required' => 'La contraseña es obligatoria.',
            'contrasenia.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'rol.required' => 'El rol es obligatorio.'
        ]);

        $usuario = new Usuario();
        $usuario->usuario = $request->usuario;
        $usuario->contrasenia = $request->contrasenia;
        $usuario->nombres = $request->nombres;
        $usuario->apellidos = $request->apellidos;
        $usuario->telefono = $request->telefono;
        $usuario->correo = $request->correo;
        $usuario->rol = $request->rol;
        $usuario->save();

        return redirect('/login')->with('success', 'Usuario registrado exitosamente');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
