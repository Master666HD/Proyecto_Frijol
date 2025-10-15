<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FrijolTech</title>
    <link rel="icon" href="{{ asset('img/core-img/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <style>
        body {
            background-image: url('{{ asset('img/bg-img/1.jpg') }}');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
        }

        .card {
            background: rgba(255, 255, 255, 0.92);
            border-radius: 18px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .form-label {
            font-weight: 600;
            color: #2d3a4b;
        }

        .btn-primary {
            background: linear-gradient(90deg, #43cea2 0%, #185a9d 100%);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, #185a9d 0%, #43cea2 100%);
        }

        .custom-file-input {
            display: none;
        }

        .custom-file-label {
            display: inline-block;
            padding: 0.5rem 1rem;
            background: #f1f1f1;
            border-radius: 8px;
            cursor: pointer;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="row w-100 justify-content-center">
            <div class="col-md-7 col-lg-6">
                <div class="card shadow p-4 rounded">
                    <h2 class="text-center mb-4">Registrar Venta o Alquiler</h2>
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('operaciones.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Agricultor:</label>
                            <select name="idUsuario" class="form-select" required>
                                @foreach ($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}">{{ $usuario->nombres }} {{ $usuario->apellidos }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tipo de operación:</label>
                            <select name="tipoOperacion" class="form-select" id="tipoOperacion" required>
                                <option value="VENTA">VENTA</option>
                                <option value="ALQUILER">ALQUILER</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Prototipo:</label>
                            <select name="idPrototipo" class="form-select" id="idPrototipo" required>
                                <option value="">Seleccione un prototipo</option>
                                @foreach ($prototipos as $proto)
                                    <option value="{{ $proto->id }}" data-estado="{{ $proto->estado }}"
                                        data-precio="{{ $proto->precio }}">
                                        {{ $proto->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Precio Bs.</label>
                            <input type="text" name="precio" class="form-control" id="precio" required readonly>
                        </div>

                        <div class="mb-3" id="fechaDevolucionField" style="display: none;">
                            <label class="form-label">Fecha de Devolución:</label>
                            <input type="date" name="fechaDevolucion" class="form-control" id="inputFechaDevolucion">
                        </div>
                        <div class="mb-3" id="observacionesField" style="display: none;">
                            <label class="form-label">Observaciones:</label>
                            <textarea name="observaciones" class="form-control" rows="3"
                                id="inputObservaciones"></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary mb-2">Registrar operación</button>
                            <a href="{{ url('/operaciones') }}" class="btn btn-secondary">Regresar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
   <script>
    const tipoOperacion = document.getElementById('tipoOperacion');
    const idPrototipo = document.getElementById('idPrototipo');
    const precioInput = document.getElementById('precio');
    const fechaDevolucionField = document.getElementById('fechaDevolucionField');
    const inputFechaDevolucion = document.getElementById('inputFechaDevolucion');
    const observacionesField = document.getElementById('observacionesField');
    const inputObservaciones = document.getElementById('inputObservaciones');

    function filtrarPrototipos() {
        const tipo = tipoOperacion.value;
        for (let option of idPrototipo.options) {
            if (!option.value) continue;
            if (tipo === 'VENTA' && option.dataset.estado === '2') {
                option.style.display = '';
            } else if (tipo === 'ALQUILER' && option.dataset.estado === '1') {
                option.style.display = '';
            } else {
                option.style.display = 'none';
            }
        }
        idPrototipo.value = '';
        precioInput.value = '';
    }

    tipoOperacion.addEventListener('change', () => {
        filtrarPrototipos();
        if (tipoOperacion.value === 'ALQUILER') {
            fechaDevolucionField.style.display = 'block';
            observacionesField.style.display = 'block';
            inputFechaDevolucion.setAttribute('required', true);
        } else {
            fechaDevolucionField.style.display = 'none';
            observacionesField.style.display = 'none';
            inputFechaDevolucion.removeAttribute('required');
        }
    });

    idPrototipo.addEventListener('change', function () {
        const selected = idPrototipo.selectedOptions[0];
        if (selected && selected.dataset.precio) {
            precioInput.value = selected.dataset.precio;
        } else {
            precioInput.value = '';
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        filtrarPrototipos();
        // Mostrar/ocultar campos según el valor inicial
        if (tipoOperacion.value === 'ALQUILER') {
            fechaDevolucionField.style.display = 'block';
            observacionesField.style.display = 'block';
            inputFechaDevolucion.setAttribute('required', true);
        } else {
            fechaDevolucionField.style.display = 'none';
            observacionesField.style.display = 'none';
            inputFechaDevolucion.removeAttribute('required');
        }
    });
</script>
</body>

</html>