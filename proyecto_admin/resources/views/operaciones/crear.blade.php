<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <title>Frijol Pairumani</title>
        <link rel="icon" href="img/core-img/favicon.ico">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
</head>
<body>
    <style>
        body {
            background: #f7f7f7;
        }
    </style>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="row w-100 justify-content-center">
            <div class="col-md-7 col-lg-6">
                <div class="card shadow p-4 rounded">
                    <h2 class="text-center mb-4">Registrar Venta o Alquiler</h2>
                    <form method="POST" action="{{ route('operacion.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Cliente (Usuario)</label>
                            <select name="idUsuario" class="form-select" required>
                                @foreach ($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}">{{ $usuario->nombres }} {{ $usuario->apellidos }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Prototipo</label>
                            <select name="idPrototipo" class="form-select" required>
                                @foreach ($prototipos as $proto)
                                    <option value="{{ $proto->id }}">{{ $proto->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tipo de operación</label>
                            <select name="tipoOperacion" class="form-select" required>
                                <option value="VENTA">VENTA</option>
                                <option value="ALQUILER">ALQUILER</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Precio</label>
                            <input type="text" name="precio" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Estado</label>
                            <select name="estado" class="form-select" required>
                                <option value="ACTIVO">ACTIVO</option>
                                <option value="FINALIZADO">FINALIZADO</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fecha de Devolución (solo alquiler)</label>
                            <input type="date" name="fechaDevolucion" class="form-control">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary mb-2">Registrar operación</button>
                            <a href="{{ route('vistaAdmin') }}" class="btn btn-secondary">Regresar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>


