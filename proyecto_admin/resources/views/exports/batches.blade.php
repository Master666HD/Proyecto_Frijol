<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Resumen de Lotes</title>
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
    <h1>Resumen de Lotes</h1>

    <table>
        <thead>
            <tr>
                <th>N° Lote</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th>Total</th>
                <th>Aptos</th>
                <th>No Aptos</th>
            </tr>
        </thead>
        <tbody>
            @foreach($summary as $item)
                <tr>
                    <td>{{ $item['lote'] }}</td>
                    <td>{{ $item['inicio'] }}</td>
                    <td>{{ $item['fin'] }}</td>
                    <td>{{ $item['total'] }}</td>
                    <td>{{ $item['aptos'] }}</td>
                    <td>{{ $item['no_aptos'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Reporte generado automáticamente. <br>
        Total de lotes: {{ count($summary) }}
    </div>
</body>
</html>
