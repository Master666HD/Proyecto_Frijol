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
        $operaciones = OperacionPrototipo::with(['usuario', 'prototipo'])->get();

        return view('operaciones.index', compact('operaciones'));
    }

    public function create()
    {
        $usuarios = Usuario::where('rol', 'Agricultor')->get();
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
            'observaciones' => 'nullable|string',
            'fechaDevolucion' => $request->tipoOperacion == 'ALQUILER' ? 'required|date' : 'nullable|date',
        ]);

        DB::beginTransaction();
        try {
            $estado = $request->tipoOperacion == 'ALQUILER' ? 'ACTIVO' : 'FINALIZADO';

            $operacion = OperacionPrototipo::create([
                'idUsuario' => $request->idUsuario,
                'idPrototipo' => $request->idPrototipo,
                'tipoOperacion' => $request->tipoOperacion,
                'precio' => $request->precio,
                'estado' => $estado,
                'fechaRegistro' => now(),
            ]);

            $prototipo = Prototipo::findOrFail($request->idPrototipo);
            if ($request->tipoOperacion == 'VENTA') {
                $prototipo->estado = 4;
            } else if ($request->tipoOperacion == 'ALQUILER') {
                $prototipo->estado = 5;
            }
            $prototipo->save();

            if ($request->tipoOperacion == 'ALQUILER') {
                DevolucionPrototipo::create([
                    'idOperacion' => $operacion->id,
                    'fechaDevolucion' => $request->fechaDevolucion,
                    'observaciones' => $request->observaciones ?? null,
                ]);
            }
            DB::commit();
            return redirect()->route('operacion.index')->with('success', 'Operación registrada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('operaciones.create')->with('error', 'Error al registrar la operación: ' . $e->getMessage());
        }
    }
    public function formDevolucion($id)
    {
        $operacion = OperacionPrototipo::with('usuario', 'prototipo')
            ->where('id', $id)
            ->where('tipoOperacion', 'ALQUILER')
            ->where('estado', 'ACTIVO')
            ->firstOrFail();

        return view('operaciones.devolucion', compact('operacion'));
    }

    public function registrarDevolucion(Request $request, $id)
    {
        $request->validate([
            'fechaDevolucion' => 'required|date',
            'observaciones' => 'nullable|string',
            'estadoPrototipo' => 'required|in:1,2,3', 
        ]);

        DB::beginTransaction();
        try {
            $devolucion = DevolucionPrototipo::where('idOperacion', $id)->firstOrFail();

            $devolucion->update([
                'fechaDevolucion' => $request->fechaDevolucion,
                'observaciones' => $request->observaciones,
            ]);

            $operacion = OperacionPrototipo::findOrFail($id);
            $operacion->update([
                'estado' => 'FINALIZADO'
            ]);

            // Cambiar el estado del prototipo según lo seleccionado
            $prototipo = Prototipo::findOrFail($operacion->idPrototipo);
            $prototipo->estado = $request->estadoPrototipo;
            $prototipo->save();

            DB::commit();
            return redirect()->route('operacion.index')->with('success', 'Devolución actualizada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar la devolución: ' . $e->getMessage());
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
