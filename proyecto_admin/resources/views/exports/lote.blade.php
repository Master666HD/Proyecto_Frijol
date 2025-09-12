<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Lote Exportado</title>
</head>
<body>
    <h1>Detalle del Lote</h1>

    @foreach($lote as $semilla)
        <p><strong>Color:</strong> {{ $semilla->color }}</p>
        <p><strong>Tamaño:</strong> {{ $semilla->tamano }}</p>
        <p><strong>Peso:</strong> {{ $semilla->peso }}</p>
        <p><strong>Estado:</strong> {{ $semilla->estado }}</p>
        <p><strong>Fecha:</strong> {{ $semilla->fechaRegistro }}</p>
        <hr>
    @endforeach
</body>
</html>
