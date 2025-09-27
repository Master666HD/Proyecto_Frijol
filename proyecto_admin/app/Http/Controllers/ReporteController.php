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
        $usuarios = Usuario::where('rol','Agricultor')->get();
        return view('reportes.index', compact('usuarios'));
    }

  public function reporteUsuario(Request $request)
{
    $request->validate([
        'idUsuario' => 'required|exists:usuarios,id',
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
        'tipo_reporte' => 'Semillas clasificadas por usuario'
    ]);

    $pdf = Pdf::loadView('reportes.reporte_usuario', [
        'usuario' => $usuario,
        'aptas' => $aptas,
        'no_aptas' => $no_aptas
        ]);

    return $pdf->download('reporte_usuario_'.$usuario->nombres.'.pdf');
}

    public function generarPorFechas(Request $request)
    {
        $request->validate([
            'idUsuario' => 'required|exists:usuarios,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $usuario = Usuario::findOrFail($request->idUsuario);

        $semillas = Semilla::where('idUsuario', $usuario->id)
            ->whereBetween('fechaRegistro', [$request->start_date, $request->end_date])
            ->get();

        $reporte = Reporte::create([
            'idUsuario' => $usuario->id,
            'tipo_reporte' => 'Semillas por Fechas',
        ]);

        $pdf = Pdf::loadView('reportes.reporte_fechas', [
            'usuario' => $usuario,
            'semillas' => $semillas,
            'start' => $request->start_date,
            'end' => $request->end_date
        ]);

        return $pdf->download('reporte_fechas_'.$usuario->nombres.'.pdf');
    }
}   