<?php
namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use App\Models\Usuario;
    use App\Models\Semilla;
    use App\Models\Reporte;
    use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
       public function index()
    {
        $usuarios = Usuario::all();
        return view('reportes.index', compact('usuarios'));
    }

  public function reporteUsuario(Request $request)
{
    $request->validate([
        'idUsuario' => 'required|exists:usuarios,id',
        'descripcion' => 'nullable|string|max:255'
    ]);
    
    $usuario_id = $request->input('idUsuario');
    $usuario = Usuario::findOrFail($usuario_id);

    $aptas = Semilla::where('idUsuario', $usuario_id)
        ->where('estado', 'APTO')
        ->count();
    $no_aptas = Semilla::where('idUsuario', $usuario_id)
        ->where('estado', 'NO APTO')
        ->count();
        
    $reporte = Reporte::create([
        'idUsuario' => $usuario->id,
        'tipo_reporte' => 'Semillas clasificadas por usuario',
        'descripcion' => $request->descripcion ?? 'Reporte de semillas del usuario ' . $usuario->nombres,
    ]);

    $pdf = Pdf::loadView('reportes.reporte_usuario', [
        'usuario' => $usuario,
        'aptas' => $aptas,
        'no_aptas' => $no_aptas,
        'descripcion' => $request->descripcion
    ]);

    return $pdf->download('reporte_usuario_'.$usuario->nombres.'.pdf');
}

    public function generarPorFechas(Request $request)
    {
        $request->validate([
            'idUsuario' => 'required|exists:usuarios,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'descripcion' => 'nullable|string|max:255'
        ]);

        $usuario = Usuario::findOrFail($request->idUsuario);

        $semillas = Semilla::where('idUsuario', $usuario->id)
            ->whereBetween('fechaRegistro', [$request->start_date, $request->end_date])
            ->get();

        $reporte = Reporte::create([
            'idUsuario' => $usuario->id,
            'tipo_reporte' => 'Semillas por Fechas',
            'descripcion' => $request->descripcion ?? 'Reporte sin descripción',
        ]);

        $pdf = Pdf::loadView('reportes.reporte_fechas', [
            'usuario' => $usuario,
            'semillas' => $semillas,
            'start' => $request->start_date,
            'end' => $request->end_date,
            'descripcion' => $request->descripcion
        ]);

        return $pdf->download('reporte_fechas_'.$usuario->nombres.'.pdf');
    }
}   