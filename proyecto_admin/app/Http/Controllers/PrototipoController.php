<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prototipo;

class PrototipoController extends Controller
{
    public function index()
    {
        $prototipos = Prototipo::where('estado', '!=', 6)->get();
        return view('prototipos.index', compact('prototipos'));
    }
    public function create()
    {
        return view('prototipos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'estado' => 'required|integer',
            'precio' => 'required|numeric',
        ]);

        $ultimo = Prototipo::orderBy('id', 'desc')->first();
        $numero = $ultimo ? ($ultimo->id + 1) : 1;
        $serial = 'PTF-' . $numero;

        $prototipo = new Prototipo($request->all());
        $prototipo->serial = $serial;
        $prototipo->save();

        return redirect()->route('prototipos.index')->with('success', 'Prototipo creado exitosamente.');
    }

    public function storeMultiple(Request $request)
    {
        $request->validate([
            'prototipos.*.nombre' => 'required|string|max:255',
            'prototipos.*.estado' => 'required|integer',
            'prototipos.*.precio' => 'required|numeric',
        ]);

        $ultimo = Prototipo::orderBy('id', 'desc')->first();
        $numero = $ultimo ? ($ultimo->id + 1) : 1;

        $data = [];
        foreach ($request->prototipos as $proto) {
            $data[] = [
                'nombre' => $proto['nombre'],
                'serial' => 'PTF-' . $numero++,
                'estado' => $proto['estado'],
                'precio' => $proto['precio'],
            ];
        }

        Prototipo::insert($data);

        return redirect()->route('prototipos.index')->with('success', 'Prototipos creados exitosamente.');
    }
    public function show(string $id)
    {

    }

    public function edit(string $id)
    {
        $prototipo = Prototipo::findOrFail($id);
        return view('prototipos.edit', compact('prototipo'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'estado' => 'required|integer',
            'precio' => 'required|numeric',
        ]);

        $prototipo = Prototipo::findOrFail($id);
        $prototipo->update($request->all());
        return redirect()->route('prototipos.index')->with('success', 'Prototipo actualizado exitosamente.');
    }
    public function destroy(string $id)
    {
        $prototipo = Prototipo::findOrFail($id);
        $prototipo->estado = 6;
        $prototipo->save();
        return redirect()->route('prototipos.index')->with('success', 'Prototipo eliminado exitosamente.');
    }
}