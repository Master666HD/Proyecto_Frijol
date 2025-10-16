@extends('menu')

@section('contenido')
    <style>
        body {
            background: linear-gradient(135deg, #0c0c0c 0%, #1a1a1a 50%, #0c0c0c 100%);
            color: #ffffff;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            padding: 2rem 0;
        }

        h2 {
            color: #ffffff;
            font-weight: 700;
            margin-bottom: 2rem;
            text-align: center;
            background: linear-gradient(135deg, #ffffff 0%, #e0e0e0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: 1px;
        }

        .card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(45, 90, 39, 0.4);
            border-radius: 20px;
            color: #fff;
            box-shadow: 0 8px 32px rgba(45, 90, 39, 0.2);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 2.5rem;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 60px rgba(45, 90, 39, 0.3);
            border-color: rgba(45, 90, 39, 0.6);
        }

        .form-label {
            color: #ffffff;
            font-weight: 600;
            margin-bottom: 0.5rem;
            letter-spacing: 0.5px;
        }

        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(45, 90, 39, 0.4);
            border-radius: 12px;
            color: #ffffff;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(45, 90, 39, 0.8);
            color: #ffffff;
            box-shadow: 0 0 0 3px rgba(45, 90, 39, 0.3);
            transform: translateY(-2px);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .form-control:hover, .form-select:hover {
            border-color: rgba(45, 90, 39, 0.6);
            transform: translateY(-1px);
        }

        .form-control:read-only, .form-select:disabled {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.3);
            color: rgba(255, 255, 255, 0.7);
            cursor: not-allowed;
        }

        .btn {
            border: none;
            border-radius: 12px;
            font-weight: 600;
            padding: 0.75rem 2rem;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2d5a27 0%, #3a6b34 100%);
            box-shadow: 0 4px 15px rgba(45, 90, 39, 0.4);
            color: #ffffff;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #3a6b34 0%, #2d5a27 100%);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(45, 90, 39, 0.6);
            color: #ffffff;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.4);
            color: #ffffff;
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #5a6268 0%, #6c757d 100%);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(108, 117, 125, 0.6);
            color: #ffffff;
        }

        .d-flex.justify-content-between {
            gap: 1rem;
        }

        /* Efecto de borde superior decorativo */
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #2d5a27, #3a6b34, #2d5a27);
            border-radius: 20px 20px 0 0;
        }

        /* Animación suave para el contenedor */
        .container {
            animation: fadeInUp 0.8s ease-out;
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

        /* Asegurar que las opciones del select sean visibles */
        .form-select option {
            background: #1a1a1a;
            color: #ffffff;
            font-weight: 600;
        }

        .row {
            margin-bottom: 1.5rem;
        }

        .mb-4 {
            margin-bottom: 2rem !important;
        }
    </style>

    <div class="container mt-5">
        <div class="card position-relative">
            <h2>MODIFICAR OPERACIÓN</h2>
            <form action="{{ route('operaciones.update', $operacion->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="idUsuario" class="form-label">USUARIO</label>
                        <select name="idUsuario" id="idUsuario" class="form-select" {{ $operacion->tipoOperacion != 'CAMBIO' ? 'disabled' : '' }}>
                            @foreach($usuarios as $usuario)
                                <option value="{{ $usuario->id }}" {{ $usuario->id == $operacion->idUsuario ? 'selected' : '' }}>
                                    {{ $usuario->nombres }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="idUsuario" value="{{ $operacion->idUsuario }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="idPrototipo" class="form-label">PROTOTIPO</label>
                        <select name="idPrototipo" id="idPrototipo" class="form-select" required>
                            <option value="">SELECCIONE UN PROTOTIPO</option>
                            @foreach($prototipos as $prototipo)
                                <option value="{{ $prototipo->id }}"
                                    data-precio-venta="{{ $prototipo->precio }}"
                                    data-precio-alquiler="{{ $prototipo->precio }}"
                                    {{ $prototipo->id == $operacion->idPrototipo ? 'selected' : '' }}>
                                    {{ $prototipo->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="tipoOperacion" class="form-label">TIPO DE OPERACIÓN</label>
                        <select name="tipoOperacion" id="tipoOperacion" class="form-select" disabled>
                            <option value="VENTA" {{ $operacion->tipoOperacion == 'VENTA' ? 'selected' : '' }}>VENTA</option>
                            <option value="ALQUILER" {{ $operacion->tipoOperacion == 'ALQUILER' ? 'selected' : '' }}>ALQUILER</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="precio" class="form-label">PRECIO</label>
                        <input type="number" name="precio" id="precio" class="form-control" value="{{ $operacion->precio }}" readonly>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="estado" class="form-label">ESTADO</label>
                        <select name="estado" id="estado" class="form-select" required>
                            <option value="ACTIVO" {{ $operacion->estado == 'ACTIVO' ? 'selected' : '' }}>ACTIVO</option>
                            <option value="FINALIZADO" {{ $operacion->estado == 'FINALIZADO' ? 'selected' : '' }}>FINALIZADO</option>
                            <option value="ANULADO" {{ $operacion->estado == 'ANULADO' ? 'selected' : '' }}>ANULADO</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('operaciones.index') }}" class="btn btn-secondary">← VOLVER</a>
                    <button type="submit" class="btn btn-primary">ACTUALIZAR OPERACIÓN</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tipoOperacion = document.getElementById('tipoOperacion');
            const idPrototipo = document.getElementById('idPrototipo');
            const precio = document.getElementById('precio');

            function actualizarPrecio() {
                const prototipo = idPrototipo.options[idPrototipo.selectedIndex];
                const tipo = tipoOperacion.value;

                if (!prototipo.value) return;

                const precioVenta = prototipo.getAttribute('data-precio-venta');
                const precioAlquiler = prototipo.getAttribute('data-precio-alquiler');

                if (tipo === 'VENTA') {
                    precio.value = precioVenta;
                } else if (tipo === 'ALQUILER') {
                    precio.value = precioAlquiler;
                }
            }

            tipoOperacion.addEventListener('change', actualizarPrecio);
            idPrototipo.addEventListener('change', actualizarPrecio);
        });
    </script>
@endsection