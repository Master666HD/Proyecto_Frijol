<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Prototipos en Mantenimiento</title>
    <link rel="stylesheet" href="{{ public_path('css/reporte.css') }}">
</head>

<body>
    <header>
        <h2>Reporte de Prototipos disponibles</h2>
    </header>
    <table>
        <thead>
            <tr>
                <th>Nro</th>
                <th>Nombre</th>
                <th>Serial</th>
                <th>Precio (Bs)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($prototipos as $prototipo)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $prototipo->nombre }}</td>
                    <td>{{ $prototipo->serial }}</td>
                    <td>{{ number_format($prototipo->precio, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No hay prototipos disponibles.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
      <footer>
        Generado el {{ \Carbon\Carbon::now('America/La_Paz')->format('d/m/Y H:i') }}
    </footer>
</body>

</html>