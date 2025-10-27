<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Recibo de Venta #{{ $oper->id }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 12px; color: #333; }
        .header { text-align:center; margin-bottom:20px; }
        .company { font-weight:700; font-size:16px; }
        .section { margin-bottom:12px; }
        table { width:100%; border-collapse: collapse; }
        th, td { padding:8px; border: 1px solid #ddd; text-align:left; }
        .right { text-align:right; }
        .total { font-weight:700; font-size:14px; }
        .footer { margin-top:30px; font-size:11px; text-align:center; color:#666; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company">Proyecto Frijol</div>
        <div>Recibo de Venta</div>
        <div>Fecha: {{ now()->format('Y-m-d H:i') }}</div>
        <div>Recibo N°: {{ $oper->id }}</div>
    </div>

    <div class="section">
        <strong>Datos del cliente:</strong>
        <table>
            <tr>
                <th>Nombre</th>
                <td>{{ $oper->usuario->nombres ?? ($oper->idUsuario ?? '---') }}</td>
                <th>Teléfono</th>
                <td>{{ $oper->usuario->telefono ?? '---' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <strong>Equipo / Prototipo</strong>
        <table>
            <tr>
                <th>Equipo</th>
                <td>{{ $oper->prototipo->nombre ?? '---' }}</td>
                <th>Serial</th>
                <td>{{ $oper->prototipo->serial ?? '---' }}</td>
            </tr>
        </table>
    </div>

    <div style="text-align:right; margin-top:12px;">
        <div class="total">TOTAL: {{ number_format($monto,2,',','.') }} Bs</div>
    </div>

    <div style="margin-top:40px;">
        <table style="border:none;">
            <tr>
                <td style="border:none; width:50%; text-align:center;">
                    ___________________________<br>
                    Firma (Cliente)
                </td>
                <td style="border:none; width:50%; text-align:center;">
                    ___________________________<br>
                    Firma (Empresa)
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Gracias por tu compra !!.
    </div>
</body>
</html>
