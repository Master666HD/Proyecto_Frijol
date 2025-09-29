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
        <div class="col-md-10 col-lg-9">
            <div class="card p-4 rounded">
                <h2 class="text-center mb-4 text-white">Agregar Prototipos</h2>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('prototipos.storeMultiple') }}" method="POST" id="multiForm">
                    @csrf
                    <table class="table table-dark table-bordered align-middle" id="prototiposTable">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Estado</th>
                                <th>Precio (Bs.)</th>
                                <th>Eliminar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="text" name="prototipos[0][nombre]" class="form-control" required></td>
                                <td>
                                    <select name="prototipos[0][estado]" class="form-select" required>
                                        <option value="1">Para alquilar</option>
                                        <option value="2">Para vender</option>
                                        <option value="3">Mantenimiento</option>
                                    </select>
                                </td>
                                <td><input type="number" step="0.01" name="prototipos[0][precio]" class="form-control" required></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm remove-row" disabled>&times;</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-primary mb-2" id="addRowBtn">Agregar otro prototipo</button>
                        <button type="submit" class="btn btn-success mb-2">Guardar todos</button>
                        <a href="{{ route('prototipos.index') }}" class="btn btn-secondary">Regresar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let rowCount = 1;
        document.getElementById('addRowBtn').addEventListener('click', function() {
            const table = document.getElementById('prototiposTable').getElementsByTagName('tbody')[0];
            const newRow = table.rows[0].cloneNode(true);
            // Limpiar los valores
            Array.from(newRow.querySelectorAll('input, select')).forEach(function(input) {
                input.value = '';
            });
            // Actualizar los nombres de los campos
            newRow.querySelectorAll('input, select').forEach(function(input) {
                const name = input.getAttribute('name');
                if (name) {
                    input.setAttribute('name', name.replace(/\d+/, rowCount));
                }
            });
            // Habilitar botón de eliminar
            newRow.querySelector('.remove-row').disabled = false;
            newRow.querySelector('.remove-row').addEventListener('click', function() {
                newRow.remove();
            });
            table.appendChild(newRow);
            rowCount++;
        });
        // Eliminar fila
        document.querySelectorAll('.remove-row').forEach(function(btn) {
            btn.addEventListener('click', function() {
                if (!btn.disabled) {
                    btn.closest('tr').remove();
                }
            });
        });
    });
</script>

</body>
</html>