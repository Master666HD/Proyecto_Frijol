<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Generar Reportes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Para agregar una imagen de fondo a toda la página, puedes poner este estilo aquí: -->
    <style>
        body {
            background-image: url('{{ asset('img/plantas.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .card {
    background-color: rgba(60, 60, 60, 0.8);
    color: white;
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}
    </style> 
</head>
<body>
 <div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg" style="width: 100%; max-width: 500px;">
        <div class="card-body">
            <h2 class="mb-4 text-center">Generar Reportes</h2>
            <form id="formReportes" action="" method="GET">
                @csrf
                <div class="form-group mb-3">
                    <label for="tipo_reporte">Tipo de Reporte</label>
                    <select name="tipo_reporte" id="tipo_reporte" class="form-control" required>
                        <option value="">-- Seleccione un tipo de reporte --</option>
                        <option value="usuario">Por Usuario (Aptas vs No Aptas)</option>
                        <option value="fechas">Por Fechas</option>
                    </select>
                </div>
                <div id="filtros"></div>
                <button type="submit" class="btn btn-primary mt-3 w-100">
                    Generar Reporte formato (PDF)
                </button>
                <a href="{{ route('vistaAdmin') }}" class="btn btn-secondary mt-3 w-100">Regresar</a>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('tipo_reporte').addEventListener('change', function () {
    let tipo = this.value;
    let filtrosDiv = document.getElementById('filtros');
    filtrosDiv.innerHTML = '';

    if (tipo === 'usuario') {
        filtrosDiv.innerHTML = `
            <div class="form-group mb-3">
                <label for="idUsuario">Seleccionar Usuario</label>
                <select name="idUsuario" class="form-control" required>
                    @foreach($usuarios as $usuario)
                        <option value="{{ $usuario->id }}">{{ $usuario->nombres }} {{ $usuario->apellidos }}</option>
                    @endforeach
                </select>
                <div class="form-group mb-3">
                    <label for="descripcion">Descripción</label>
                    <input type="text" class="form-control" name="descripcion" required>
                </div>
            </div>
        `;
        document.getElementById('formReportes').action = "{{ route('reportes.usuario.pdf') }}";
    }

    if (tipo === 'fechas') {
        filtrosDiv.innerHTML = `
            <div class="form-group mb-3">
                <label for="idUsuario">Seleccionar Usuario</label>
                <select name="idUsuario" class="form-control" required>
                    @foreach($usuarios as $usuario)
                        <option value="{{ $usuario->id }}">{{ $usuario->nombres }} {{ $usuario->apellidos }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mb-3">
                <label for="start_date">Fecha de Inicio</label>
                <input type="date" class="form-control" name="start_date" required>
            </div>
            <div class="form-group mb-3">
                <label for="end_date">Fecha Final</label>
                <input type="date" class="form-control" name="end_date" required>
            </div>
            <div class="form-group mb-3">
                <label for="descripcion">Descripción</label>
                <input type="text" class="form-control" name="descripcion" >
            </div>
        `;
        document.getElementById('formReportes').action = "{{ route('reportes.fechas.pdf') }}";
    }
});
</script>
</body>
</html>
