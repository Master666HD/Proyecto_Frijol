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

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }




}
