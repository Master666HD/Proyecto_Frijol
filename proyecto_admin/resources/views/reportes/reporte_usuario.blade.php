<!-- resources/views/reportes/reporte_usuario.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Semillas por Usuario</title>
    <style>
        body { 
            font-family: Arial, Helvetica, sans-serif; 
            font-size: 14px; 
            color: #333; 
            margin: 20px; 
        }
        h1, h2 { 
            text-align: center; 
            color: #2c3e50; 
        }
        p { margin: 5px 0; }
        .header { 
            text-align: center; 
            margin-bottom: 20px; 
            border-bottom: 2px solid #2c3e50; 
            padding-bottom: 10px;
        }
        .info {
            margin-bottom: 15px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
        }
        th, td { 
            border: 1px solid #000; 
            padding: 10px; 
            text-align: center; 
        }
        th {
            background-color: #f2f2f2;
            color: #2c3e50;
        }
        .footer {
            margin-top: 30px; 
            text-align: right; 
            font-size: 12px; 
            color: #555;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Reporte de Semillas Clasificadas</h1>
        <h2>Usuario: {{ $usuario->nombres }} {{ $usuario->apellidos }}</h2>
    </div>

    <div class="info">
        <p><strong>Correo:</strong> {{ $usuario->correo }}</p>
        <p><strong>Teléfono:</strong> {{ $usuario->telefono }}</p>
        @if(!empty($descripcion))
            <p><strong>Descripción:</strong> {{ $descripcion }}</p>
        @endif
        <p><strong>Fecha de Generación:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Total Semillas Aptas</th>
                <th>Total Semillas No Aptas</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $aptas }}</td>
                <td>{{ $no_aptas }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Generado automáticamente por el Sistema de Clasificación de Frijol
    </div>

</body>
</html>
