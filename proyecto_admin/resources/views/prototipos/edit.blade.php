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
            background-image: url('{{ asset('img/crear.jpg') }}');
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
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="row w-100 justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card p-4 rounded">
                    <h2 class="text-center mb-4">Editar Prototipo</h2>
                    <form action="{{ route('prototipos.update', $prototipo->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre:</label>
                            <input type="text" id="nombre" name="nombre" class="form-control"
                                value="{{ $prototipo->nombre }}">
                        </div>
                        <div class="mb-3">
                            <label for="serial" class="form-label">Serial:</label>
                            <input type="text" id="serial" name="serial" class="form-control"
                                value="{{ $prototipo->serial }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado:</label>
                            <select id="estado" name="estado" class="form-select" required>
                                <option value="1" {{ $prototipo->estado == 1 ? 'selected' : '' }}
                                    @if($prototipo->estado == 2) disabled @endif>Para alquilar</option>
                                <option value="2" {{ $prototipo->estado == 2 ? 'selected' : '' }}>Para vender</option>
                                <option value="3" {{ $prototipo->estado == 3 ? 'selected' : '' }}>Mantenimiento</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio (Bs.):</label>
                            <input type="number" step="0.01" id="precio" name="precio" class="form-control"
                                value="{{ $prototipo->precio }}" required>
                        </div>
                        <div class="mb-3" id="observaciones-group"
                            style="display: {{ $prototipo->estado == 3 ? '' : 'none' }};">
                            <label for="observaciones" class="form-label">Motivo de mantenimiento:</label>
                            <input type="text" id="observaciones" name="observaciones" class="form-control"
                                value="{{ $prototipo->estado == 3 ? $prototipo->observaciones : '' }}" maxlength="250"
                                {{ $prototipo->estado == 3 ? 'required' : '' }}>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning mb-2">Actualizar Prototipo</button>
                            <a href="{{ route('prototipos.index') }}" class="btn btn-secondary">Regresar</a>
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