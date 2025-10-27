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
            background-image: url('{{ asset('img/bg-img/nose2.png') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            position: relative;
        }

        /* Overlay oscuro para mejorar legibilidad */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: -1;
        }

        .card {
            background: rgba(30, 30, 30, 0.95);
            backdrop-filter: blur(20px);
            border: 2px solid rgba(45, 90, 39, 0.8);
            border-radius: 20px;
            color: #ffffff;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.8);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 80px rgba(45, 90, 39, 0.6);
            border-color: rgba(45, 90, 39, 1);
        }

        h2 {
            color: #ffffff;
            font-weight: 700;
            margin-bottom: 2rem;
            text-align: center;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.8);
            letter-spacing: 1px;
            font-size: 2.2rem;
        }

        .form-label {
            color: #ffffff;
            font-weight: 700;
            margin-bottom: 0.5rem;
            letter-spacing: 0.5px;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.8);
            font-size: 1rem;
        }

        .form-control, .form-select, textarea {
            background: rgba(255, 255, 255, 0.12);
            border: 2px solid rgba(45, 90, 39, 0.6);
            border-radius: 12px;
            color: #ffffff;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .form-control:focus, .form-select:focus, textarea:focus {
            background: rgba(255, 255, 255, 0.18);
            border-color: rgba(45, 90, 39, 1);
            color: #ffffff;
            box-shadow: 0 0 0 4px rgba(45, 90, 39, 0.4), inset 0 2px 4px rgba(0, 0, 0, 0.3);
            transform: translateY(-2px);
        }

        .form-control::placeholder, textarea::placeholder {
            color: rgba(255, 255, 255, 0.7);
            font-weight: 500;
        }

        .form-control:hover, .form-select:hover, textarea:hover {
            border-color: rgba(45, 90, 39, 0.8);
            transform: translateY(-1px);
            background: rgba(255, 255, 255, 0.15);
        }

        .form-control:read-only {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.3);
            color: rgba(255, 255, 255, 0.7);
        }

        .btn {
            border: none;
            border-radius: 12px;
            font-weight: 700;
            padding: 0.85rem 2rem;
            transition: all 0.3s ease;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-size: 1rem;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
        }

        .btn-primary {
            background: linear-gradient(135deg, #2d5a27 0%, #3a6b34 100%);
            box-shadow: 0 6px 20px rgba(45, 90, 39, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #3a6b34 0%, #2d5a27 100%);
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(45, 90, 39, 0.7);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);
            box-shadow: 0 6px 20px rgba(74, 85, 104, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%);
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(74, 85, 104, 0.7);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .alert {
            border-radius: 12px;
            backdrop-filter: blur(10px);
            font-weight: 600;
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(45, 90, 39, 0.9) 0%, rgba(58, 107, 52, 0.9) 100%);
            border: 2px solid rgba(45, 90, 39, 0.8);
            color: #ffffff;
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.9) 0%, rgba(200, 35, 51, 0.9) 100%);
            border: 2px solid rgba(220, 53, 69, 0.8);
            color: #ffffff;
        }

        .d-grid {
            gap: 1rem;
        }

        /* Efecto de borde superior decorativo */
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #2d5a27, #3a6b34, #4caf50, #3a6b34, #2d5a27);
            border-radius: 20px 20px 0 0;
        }

        /* Animación suave para el contenedor */
        .container {
            animation: fadeInUp 0.8s ease-out;
            position: relative;
            z-index: 1;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Mejorar contraste de todos los textos */
        .text-white-enhanced {
            color: #ffffff !important;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.8);
        }

        /* Asegurar que las opciones del select sean visibles */
        .form-select option {
            background: #1a1a1a;
            color: #ffffff;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="row w-100 justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card p-5 position-relative">
                    <h2 class="text-white-enhanced">REGISTRAR VENTA O ALQUILER</h2>
                    @if(session('success'))
                        <div class="alert alert-success mb-4">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger mb-4">{{ session('error') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li class="text-white-enhanced">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('operaciones.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-white-enhanced">AGRICULTOR:</label>
                            <select name="idUsuario" class="form-select" required>
                                @foreach ($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}">{{ $usuario->nombres }} {{ $usuario->apellidos }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-white-enhanced">TIPO DE OPERACIÓN:</label>
                            <select name="tipoOperacion" class="form-select" id="tipoOperacion" required>
                                <option value="VENTA">VENTA</option>
                                <option value="ALQUILER">ALQUILER</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-white-enhanced">PROTOTIPO:</label>
                            <select name="idPrototipo" class="form-select" id="idPrototipo" required>
                                <option value="">SELECCIONE UN PROTOTIPO</option>
                                @foreach ($prototipos as $proto)
                                    <option value="{{ $proto->id }}" data-estado="{{ $proto->estado }}"
                                        data-precio="{{ $proto->precio }}">
                                        {{ $proto->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-white-enhanced">PRECIO BS.</label>
                            <input type="text" name="precio" class="form-control" id="precio" required readonly>
                        </div>

                        <div class="mb-3" id="fechaDevolucionField" style="display: none;">
                            <label class="form-label text-white-enhanced">FECHA DE DEVOLUCIÓN:</label>
                            <input type="date" name="fechaDevolucion" class="form-control" id="inputFechaDevolucion">
                        </div>
                        <div class="mb-3" id="observacionesField" style="display: none;">
                            <label class="form-label text-white-enhanced">OBSERVACIONES:</label>
                            <textarea name="observaciones" class="form-control" rows="3"
                                id="inputObservaciones"></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">REGISTRAR OPERACIÓN</button>
                            <a href="{{ url('/operaciones') }}" class="btn btn-secondary">REGRESAR</a>
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

    // Obtener elementos
    const fechaDevolucionField = document.getElementById('fechaDevolucionField');
    const inputFechaDevolucion = document.getElementById('inputFechaDevolucion');
    const observacionesField = document.getElementById('observacionesField');
    const tipoOperacion = document.getElementById('tipoOperacion');

    // Configurar fecha mínima hoy
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    const todayString = `${year}-${month}-${day}`;
    inputFechaDevolucion.min = todayString;

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
    tipoOperacion.addEventListener('change', () => {
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
});

    </script>
</body>

</html>