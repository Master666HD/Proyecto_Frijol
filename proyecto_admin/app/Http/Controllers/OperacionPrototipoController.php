<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OperacionPrototipo;
use App\Models\Usuario;
use App\Models\Prototipo;

class OperacionPrototipoController extends Controller
{
    public function create()
    {
        $usuarios = Usuario::all();
        $prototipos = Prototipo::all();
        return view('operaciones.crear', compact('usuarios', 'prototipos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'idUsuario' => 'required|exists:usuarios,id',
            'idPrototipo' => 'required|exists:prototipos,id',
            'tipoOperacion' => 'required|in:VENTA,ALQUILER',
            'precio' => 'required',
            'estado' => 'required',
            'fechaDevolucion' => 'nullable|date'
        ]);

        OperacionPrototipo::create([
            'idUsuario' => $request->idUsuario,
            'idPrototipo' => $request->idPrototipo,
            'tipoOperacion' => $request->tipoOperacion,
            'precio' => $request->precio,
            'estado' => $request->estado,
            'fechaRegistro' => now(),
            'fechaDevolucion' => $request->fechaDevolucion,
        ]);

        return redirect()->route('admin')->with('success', 'Operación registrada');
    }
}