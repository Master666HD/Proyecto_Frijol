@extends('menu')

@section('contenido')
    <style>
        body {
            background-color: #000000 !important;
            color: #e4e4e4;
            font-family: 'Poppins', sans-serif;
        }

        h2, h5 {
            color: #00ff88;
        }

        .card {
            background-color: #1b1f24;
            border: 1px solid #00ff88;
            border-radius: 10px;
            color: #000000;
            box-shadow: 0 4px 10px rgba(0, 255, 136, 0.2);
            margin-bottom: 1.5rem;
            padding: 20px;
        }

        .card h5 {
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .form-label {
            color: #00ff88;
            font-weight: 500;
        }

        .form-control, .form-select {
            background-color: rgba(255, 255, 255, 0.892);
            border: 1px solid rgba(0, 255, 136, 0.4);
            color: #0c0b0b;
        }

        .form-control:focus, .form-select:focus {
            background-color: rgba(230, 230, 230, 0.985);
            color: #000000;
            box-shadow: 0 0 8px #00ff88;
            border-color: #00ff88;
        }

        .btn-primary {
            background-color: #00ff88;
            border: none;
            color: #000;
            font-weight: bold;
        }

        .btn-primary:hover {
            background-color: #00cc6e;
            color: #000;
        }

        .btn-secondary {
            background-color: #333;
            border: 1px solid #00ff88;
            color: #00ff88;
        }

        .btn-secondary:hover {
            background-color: #00ff88;
            color: #ffffff;
        }
    </style>

    <div class="container mt-5">
        <div class="card">
            <h2 class="mb-4">Modificar Operación</h2>
            <form action="{{ route('operaciones.update', $operacion->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="idUsuario" class="form-label">Usuario</label>
                        <select name="idUsuario" id="idUsuario" class="form-select" {{ $operacion->tipoOperacion != 'CAMBIO' ? 'disabled' : '' }}>
                            @foreach($usuarios as $usuario)
                                <option value="{{ $usuario->id }}" {{ $usuario->id == $operacion->idUsuario ? 'selected' : '' }}>
                                    {{ $usuario->nombres }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="idUsuario" value="{{ $operacion->idUsuario }}">
                    </div>

                    <div class="col-md-6">
                        <label for="idPrototipo" class="form-label">Prototipo</label>
                        <select name="idPrototipo" id="idPrototipo" class="form-select" required>
                            <option value="">Seleccione un prototipo</option>
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

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="tipoOperacion" class="form-label">Tipo de operación</label>
                        <select name="tipoOperacion" id="tipoOperacion" class="form-select" disabled>
                            <option value="VENTA" {{ $operacion->tipoOperacion == 'VENTA' ? 'selected' : '' }}>Venta</option>
                            <option value="ALQUILER" {{ $operacion->tipoOperacion == 'ALQUILER' ? 'selected' : '' }}>Alquiler</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="precio" class="form-label">Precio</label>
                        <input type="number" name="precio" id="precio" class="form-control" value="{{ $operacion->precio }}" readonly>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="estado" class="form-label">Estado</label>
                        <select name="estado" id="estado" class="form-select" required>
                            <option value="ACTIVO" {{ $operacion->estado == 'ACTIVO' ? 'selected' : '' }}>ACTIVO</option>
                            <option value="FINALIZADO" {{ $operacion->estado == 'FINALIZADO' ? 'selected' : '' }}>FINALIZADO</option>
                            <option value="ANULADO" {{ $operacion->estado == 'ANULADO' ? 'selected' : '' }}>ANULADO</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('operaciones.index') }}" class="btn btn-secondary">← Volver</a>
                    <button type="submit" class="btn btn-primary">Actualizar Operación</button>
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
