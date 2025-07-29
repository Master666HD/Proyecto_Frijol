<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Semillas por Fechas</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2, h4 { text-align: center; margin: 0; }
        .info { margin-bottom: 20px; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }
        th {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>

    <h2>Reporte de Semillas Clasificadas</h2>
    <h4>Usuario: {{ $usuario->nombres }} {{ $usuario->apellidos }}</h4>

    <div class="info">
        <p><strong>Período:</strong> {{ $start }} al {{ $end }}</p>
        <p><strong>Correo:</strong> {{ $usuario->correo }}</p>
        <p><strong>Teléfono:</strong> {{ $usuario->telefono }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Peso (g)</th>
                <th>Tamaño</th>
                <th>Color</th>
                <th>Estado</th>
                <th>Fecha Registro</th>
            </tr>
        </thead>
        <tbody>
            @forelse($semillas as $index => $semilla)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $semilla->peso }}</td>
                    <td>{{ $semilla->tamano }}</td>
                    <td>{{ $semilla->color }}</td>
                    <td>{{ $semilla->estado }}</td>
                    <td>{{ $semilla->fechaRegistro }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No se encontraron registros en este rango de fechas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <br>
    <p><strong>Total semillas encontradas:</strong> {{ $semillas->count() }}</p>

</body>
</html>
