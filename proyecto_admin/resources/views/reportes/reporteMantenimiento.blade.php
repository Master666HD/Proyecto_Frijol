<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Prototipos en Mantenimiento</title>
    <link rel="stylesheet" href="{{ public_path('css/reporte.css') }}">
</head>

<body>
    <header>
        <h2>Reporte de Prototipos en Mantenimiento</h2>
    </header>
    <table>
        <thead>
            <tr>
                <th>Nro</th>
                <th>Nombre</th>
                <th>Serial</th>
                <th>Precio (Bs)</th>
                <th>Observaciones</th>
                <th>Fecha Registro</th>
            </tr>
        </thead>
        <tbody>
            @forelse($prototipos as  $prototipo)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $prototipo->nombre }}</td>
                    <td>{{ $prototipo->serial }}</td>
                    <td>{{ number_format($prototipo->precio, 2) }}</td>
                    <td>{{ $prototipo->observaciones ?? '---' }}</td>
                    <td>{{ \Carbon\Carbon::parse($prototipo->fechaRegistro)->format('Y/m/d') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No hay prototipos en mantenimiento.</td>
                </tr>
            @endforelse\
        </tbody>
    </table>

    <h3>Total en mantenimiento:
        {{ number_format($prototipos->sum('precio'), 2) }} Bs
    </h3>
</body>

</html>