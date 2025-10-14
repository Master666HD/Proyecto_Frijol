<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Resumen de Lotes</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; margin: 25px; color: #222; }
        h1 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #999; padding: 8px; text-align: center; }
        th { background: #f0f0f0; font-weight: bold; }
        tr:nth-child(even) { background: #f9f9f9; }
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
</body>
</html>
