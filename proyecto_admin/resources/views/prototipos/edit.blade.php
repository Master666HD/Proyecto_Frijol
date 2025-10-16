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

        .form-control, .form-select {
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

        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.18);
            border-color: rgba(45, 90, 39, 1);
            color: #ffffff;
            box-shadow: 0 0 0 4px rgba(45, 90, 39, 0.4), inset 0 2px 4px rgba(0, 0, 0, 0.3);
            transform: translateY(-2px);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
            font-weight: 500;
        }

        .form-control:hover, .form-select:hover {
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

        .btn-warning {
            background: linear-gradient(135deg, #FFA000 0%, #FF8C00 100%);
            box-shadow: 0 6px 20px rgba(255, 160, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #ffffff;
        }

        .btn-warning:hover {
            background: linear-gradient(135deg, #FF8C00 0%, #FFA000 100%);
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(255, 160, 0, 0.7);
            border-color: rgba(255, 255, 255, 0.3);
            color: #ffffff;
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

        /* Estilo para opciones deshabilitadas */
        .form-select option:disabled {
            background: #2d3748;
            color: #a0aec0;
        }

        /* Mejorar el contraste del card */
        .card {
            background: linear-gradient(135deg, rgba(30, 30, 30, 0.95) 0%, rgba(40, 40, 40, 0.95) 100%);
        }
    </style>

    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="row w-100 justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card p-5 position-relative">
                    <h2 class="text-white-enhanced">EDITAR PROTOTIPO</h2>
                    <form action="{{ route('prototipos.update', $prototipo->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="nombre" class="form-label text-white-enhanced">Nombre:</label>
                            <input type="text" id="nombre" name="nombre" class="form-control"
                                value="{{ $prototipo->nombre }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="serial" class="form-label text-white-enhanced">Serial:</label>
                            <input type="text" id="serial" name="serial" class="form-control"
                                value="{{ $prototipo->serial }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="estado" class="form-label text-white-enhanced">Estado:</label>
                            <select id="estado" name="estado" class="form-select" required>
                                <option value="1" {{ $prototipo->estado == 1 ? 'selected' : '' }}
                                    @if($prototipo->estado == 2) disabled @endif>Para alquilar</option>
                                <option value="2" {{ $prototipo->estado == 2 ? 'selected' : '' }}>Para vender</option>
                                <option value="3" {{ $prototipo->estado == 3 ? 'selected' : '' }}>Mantenimiento</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="precio" class="form-label text-white-enhanced">Precio (Bs.):</label>
                            <input type="number" step="0.01" id="precio" name="precio" class="form-control"
                                value="{{ $prototipo->precio }}" required>
                        </div>
                        <div class="mb-3" id="observaciones-group"
                            style="display: {{ $prototipo->estado == 3 ? '' : 'none' }};">
                            <label for="observaciones" class="form-label text-white-enhanced">Motivo de mantenimiento:</label>
                            <input type="text" id="observaciones" name="observaciones" class="form-control"
                                value="{{ $prototipo->estado == 3 ? $prototipo->observaciones : '' }}" maxlength="250"
                                {{ $prototipo->estado == 3 ? 'required' : '' }}>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning">ACTUALIZAR PROTOTIPO</button>
                            <a href="{{ route('prototipos.index') }}" class="btn btn-secondary">REGRESAR</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const estadoSelect = document.getElementById('estado');
            const observacionesGroup = document.getElementById('observaciones-group');
            const observacionesInput = document.getElementById('observaciones');

            function toggleObservaciones() {
                if (estadoSelect.value == '3') {
                    observacionesGroup.style.display = '';
                    observacionesInput.required = true;
                } else {
                    observacionesGroup.style.display = 'none';
                    observacionesInput.required = false;
                    observacionesInput.value = '';
                }
            }

            estadoSelect.addEventListener('change', toggleObservaciones);
            toggleObservaciones(); 
        });
    </script>
</body>

</html>