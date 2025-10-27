<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>FrijolTech</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
</head>
<style>
    body {
        background-image: url('{{ asset('img/fondo-devolucion.jpg') }}');
        background-size: cover;
        background-position: center;
        min-height: 100vh;
    }

    .card {
        background-color: rgba(60, 60, 60, 0.8);
        color: white;
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
</style>
<body>
    <div class="container">
        <div class="card shadow p-4 mt-5">
            <h3 class="mb-3">Registro de la Devolución</h3>

            <p><strong>Agricultor:</strong> {{ $operacion->usuario->nombres }} {{ $operacion->usuario->apellidos }}</p>
            <p><strong>Prototipo:</strong> {{ $operacion->prototipo->nombre }}</p>
            <p><strong>Fecha de devolución actual:</strong>
                {{ \Carbon\Carbon::parse($devolucion->fechaDevolucion)->format('d/m/Y') }}
            </p>

            <p><strong>Fecha de registro:</strong>
                {{ \Carbon\Carbon::parse($operacion->fechaRegistro)->format('d/m/Y') }}
            </p>

            {{-- 🔹 Campos nuevos --}}
            <hr>
            <p><strong>Precio del prototipo:</strong> Bs {{ number_format($operacion->prototipo->precio, 2) }}</p>
            <p><strong>Ganancia (3%):</strong>
                Bs {{ number_format($operacion->prototipo->precio * 0.03, 2) }}
            </p>
            <p><strong>Monto a devolver:</strong>
                Bs {{ number_format($operacion->prototipo->precio - ($operacion->prototipo->precio * 0.03), 2) }}
            </p>
            <hr>

            <form action="{{ route('operaciones.devolucion.form', $operacion->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <p><strong>Mandar:</strong></p>
                    <select name="estadoPrototipo" class="form-select" required>
                        <option value="1">Para alquilar</option>
                        <option value="2">Para vender</option>
                        <option value="3">Mantenimiento</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Observaciones:</label>
                    <textarea name="observaciones" class="form-control" rows="3">{{ $devolucion->observaciones ?? '' }}</textarea>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Registrar devolución</button>
                    <a href="{{ route('operaciones.index') }}" class="btn btn-secondary mt-2">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
