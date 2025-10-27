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
        $operaciones = OperacionPrototipo::with(['usuario', 'prototipo'])->where('estado', 'ACTIVO')->orWhere('estado', 'FINALIZADO')->get();
        $prototiposDisponibles = Prototipo::whereIn('estado', [1, 2])->exists();
        return view('operaciones.index', compact('operaciones', 'prototiposDisponibles'));
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
            $operacion = OperacionPrototipo::create([
                'idUsuario' => $request->idUsuario,
                'idPrototipo' => $request->idPrototipo,
                'tipoOperacion' => $request->tipoOperacion,
                'precio' => $request->precio,
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
            return redirect()->route('operaciones.index')->with('success', 'Operación registrada exitosamente.');
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
        $devolucion = DevolucionPrototipo::where('idOperacion', $id) -> where('fechaDevolucion', '!=', null)->first();

        return view('operaciones.devolucion', compact('operacion', 'devolucion'));
    }

    public function registrarDevolucion(Request $request, $id)
    {
        $request->validate([
            'observaciones' => 'nullable|string',
            'estadoPrototipo' => 'required|in:1,2,3',
        ]);

        DB::beginTransaction();
        try {
            $devolucion = DevolucionPrototipo::where('idOperacion', $id)->firstOrFail();

            $operacion = OperacionPrototipo::findOrFail($id);
            $prototipo = Prototipo::findOrFail($operacion->idPrototipo);

            // 🔹 Calcular ganancia (5% del precio original)
            $ganancia = $prototipo->precio * 0.05;

            // 🔹 Actualizar el precio de la operación a la ganancia
            $operacion->update(['precio' => $ganancia]);

            // 🔹 Actualizar la devolución y estado del prototipo
            $devolucion->update([
                'observaciones' => $request->observaciones
            ]);

            $operacion->update(['estado' => 'FINALIZADO']);
            $prototipo->update(['estado' => $request->estadoPrototipo]);

            DB::commit();

            return redirect()->route('operaciones.index')
                ->with('success', 'Devolución registrada correctamente, precio actualizado a ganancia.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar la devolución: ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {

    }

    public function edit($id)
    {
        $operacion = OperacionPrototipo::findOrFail($id);
        $usuarios = Usuario::all();

        // Filtrar prototipos disponibles según el tipo de operación
        if ($operacion->tipoOperacion === 'VENTA') {
            $prototipos = Prototipo::where('estado', 2)
                ->orWhere('id', $operacion->idPrototipo) // incluir el actual
                ->get();
        } else { // ALQUILER
            $prototipos = Prototipo::where('estado', 1)
                ->orWhere('id', $operacion->idPrototipo)
                ->get();
        }

        return view('operaciones.edit', compact('operacion', 'usuarios', 'prototipos'));
    }


   public function update(Request $request, $id)
{
    $operacion = OperacionPrototipo::findOrFail($id);

    $request->validate([
        'idPrototipo' => 'required|exists:prototipos,id',
        'estado' => 'required|in:ACTIVO,FINALIZADO,ANULADO',
        'precio' => 'required|numeric',
        'idUsuario' => 'required|exists:usuarios,id'
    ]);

    DB::beginTransaction();
    try {
        // Si se cambió el prototipo
        if ($operacion->idPrototipo != $request->idPrototipo) {
            $prototipoAnterior = Prototipo::find($operacion->idPrototipo);
            if ($prototipoAnterior) {
                // liberar el anterior
                if ($operacion->tipoOperacion === 'VENTA') {
                    $prototipoAnterior->estado = 2; // disponible para venta
                } else {
                    $prototipoAnterior->estado = 1; // disponible para alquiler
                }
                $prototipoAnterior->save();
            }
        }

        // Actualizar operación
        $operacion->update([
            'idUsuario' => $request->idUsuario,
            'idPrototipo' => $request->idPrototipo,
            'precio' => $request->precio,
            'estado' => $request->estado,
        ]);

        // Cambiar estado del nuevo prototipo
        $prototipoNuevo = Prototipo::findOrFail($request->idPrototipo);
        if ($operacion->tipoOperacion === 'VENTA') {
            $prototipoNuevo->estado = 4; // vendido
        } else {
            $prototipoNuevo->estado = 5; // alquilado
        }
        $prototipoNuevo->save();

        DB::commit();
        return redirect()->route('operaciones.index')->with('success', 'Operación actualizada correctamente.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Error al actualizar: ' . $e->getMessage());
    }
}


    public function destroy($id)
    {
        try {
            $operacion = OperacionPrototipo::findOrFail($id);

            if ($operacion->estado !== 'ANULADO') {

                $prototipo = Prototipo::findOrFail($operacion->idPrototipo);

                $operacion->estado = 'ANULADO';
                $operacion->save();

                if ($operacion->tipoOperacion === 'VENTA') {
                    $prototipo->estado = 2;
                } elseif ($operacion->tipoOperacion === 'ALQUILER') {
                    $prototipo->estado = 1;
                }
                

                $prototipo->save();
            }

            return redirect()->route('operaciones.index')->with('success', 'Operación anulada correctamente y prototipo liberado.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al anular la operación: ' . $e->getMessage());
        }
    }
   public function destroyWithPrototipo($id)
{
    try {
        $operacion = OperacionPrototipo::findOrFail($id);

        // Validar que sea alquiler finalizado
        if ($operacion->tipoOperacion !== 'ALQUILER' || $operacion->estado !== 'FINALIZADO') {
            return back()->with('error', 'Solo se pueden anular alquileres finalizados con esta acción.');
        }

        $prototipo = Prototipo::findOrFail($operacion->idPrototipo);

        // Cambiar estados en lugar de eliminar
        $operacion->estado = 'ANULADO';
        $operacion->save();

        $prototipo->estado = 6; // o el estado que corresponda a "liberado"
        $prototipo->save();

        return redirect()->route('operaciones.index')->with('success', 'Operación y prototipo anulados correctamente.');
    } catch (\Exception $e) {
        return back()->with('error', 'Error al anular: ' . $e->getMessage());
    }
}



}
