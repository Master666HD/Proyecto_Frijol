<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
class UserController extends Controller
{
    
    public function index()
    {
        $usuarios = Usuario::all();

    return view('usuarios.vistaUsuarios', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Mostrar el formulario para crear un nuevo usuario
        return view('usuarios.crearUsuario');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar y almacenar el nuevo usuario
        $validated = $request->validate([
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'correo' => 'required|email|max:255|unique:usuarios,correo',
            'rol' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'usuario' => 'required|string|max:255|unique:usuarios,usuario',
            'contrasenia' => 'required|string|min:8|confirmed', // Aseg
        ]);

        Usuario::create($validated);
        return redirect()->route('usuarios.index')->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Obtener el usuario por ID y mostrar el formulario de edición
        $usuarios = Usuario::findOrFail($id);
        return view('usuarios.editarUsuario', compact('usuarios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validar y actualizar el usuario
        $validated = $request->validate([
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'correo' => 'required|email|max:255|unique:usuarios,correo,' . $id,
            'rol' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'usuario' => 'required|string|max:255|unique:usuarios,usuario,' . $id,
            'contrasenia' => 'nullable|string|min:8|confirmed', //
        ]);

        $usuarios = Usuario::findOrFail($id);
        $usuarios->update($validated);
        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Eliminar el usuario por ID
        $usuarios = Usuario::findOrFail($id);
        $usuarios->delete();
        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado exitosamente.');
    }
}
