<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Reporte Ventas</title>
    <link rel="stylesheet" href="{{ public_path('css/reporte.css') }}">
</head>

<body>
    <header>
        <h2>Reporte de Facturación por Venta</h2>
        <p class="periodo">Periodo: {{ $inicio ?? '---' }} a {{ $fin ?? '---' }}</p>
    </header>

    <table>
        <thead>
            <tr>
                <th>Nro</th>
                <th>Prototipo</th>
                <th>Precio (Bs)</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @forelse($operaciones as $op)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $op->prototipo->nombre ?? '---' }}</td>
                    <td>{{ number_format($op->precio, 2) }}</td>
                    <td>{{ \Carbon\Carbon::parse($op->fechaRegistro)->format('Y/m/d') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No se encontraron registros</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p class="total">Total: {{ number_format($total, 2) }} Bs</p>

    <footer>
        Generado el {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
    </footer>
</body>

</html>