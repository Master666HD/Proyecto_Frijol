<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Lote</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #2E7D32;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px 15px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f3f3f3;
        }

        tr:hover {
            background-color: #e0f2f1;
        }

        .footer {
            text-align: center;
            font-size: 0.9em;
            color: #555;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Detalle del Lote</h1>

    <table>
        <thead>
            <tr>
                <th>Color</th>
                <th>Tamaño</th>
                <th>Peso</th>
                <th>Estado</th>
                <th>Fecha de Registro</th>
            </tr>
        </thead>
        <tbody>
            @foreach($batch as $seed)
            <tr>
                <td>{{ $seed['color'] }}</td>
                <td>{{ $seed['size'] }}</td>
                <td>{{ $seed['weight'] }}</td>
                <td>{{ $seed['status'] }}</td>
                <td>{{ $seed['registration_date'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Reporte generado automáticamente. <br>
        Total de semillas: {{ count($batch) }}
    </div>
</body>
</html>
