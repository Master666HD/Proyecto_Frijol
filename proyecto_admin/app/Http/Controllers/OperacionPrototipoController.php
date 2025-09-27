<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OperacionPrototipo;
use App\Models\Prototipo;
use App\Models\Usuario;
use App\Models\DevolucionPrototipo;
use Illuminate\Support\Facades\DB;

class OperacionPrototipoController extends Controller
{
    
    public function index()
    {
        //
    }

    
    public function create()
    {
        $usuarios = Usuario::where('rol','Agricultor')->get();
        $prototipos = Prototipo::all();
        return view('operaciones.crear', compact('usuarios', 'prototipos'));
    }


public function store(Request $request)
{
    $request->validate([
        'idUsuario' => 'required|exists:usuarios,id',
        'idPrototipo' => 'required|exists:prototipos,id',
        'tipoOperacion' => 'required|in:VENTA,ALQUILER',
        'precio' => 'required|numeric',
        'estado' => 'required|string',
        'observaciones' => 'nullable|string',
        'fechaDevolucion' => $request->tipoOperacion == 'ALQUILER' ? 'required|date' : 'nullable|date',
    ]);

    DB::beginTransaction();
    try {
        $estado = $request->tipoOperacion == 'VENTA' ? 'FINALIZADO' : $request->estado;

        $operacion = OperacionPrototipo::create([
            'idUsuario' => $request->idUsuario,
            'idPrototipo' => $request->idPrototipo,
            'tipoOperacion' => $request->tipoOperacion,
            'precio' => $request->precio,
            'estado' => $estado,
            'fechaRegistro' => now(),
            'fechaDevolucion' => $request->tipoOperacion == 'ALQUILER' ? $request->fechaDevolucion : null,
        ]);

        if ($request->tipoOperacion == 'ALQUILER') {
            DevolucionPrototipo::create([
                'idOperacion' => $operacion->id,
                'fechaDevolucion' => $request->fechaDevolucion,
                'observaciones' => $request->observaciones ?? null,
            ]);
        }
        DB::commit();
        return redirect()->route('operacion.create')->with('success', 'Operación registrada exitosamente.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->route('operacion.create')->with('error', 'Error al registrar la operación: ' . $e->getMessage());
    }
}
    public function show(string $id)
    {
        
    }

    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
