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

        .table {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .table thead th {
            background: linear-gradient(135deg, rgba(45, 90, 39, 0.3) 0%, rgba(58, 107, 52, 0.2) 100%);
            border-bottom: 2px solid rgba(45, 90, 39, 0.6);
            color: #ffffff;
            font-weight: 700;
            padding: 1rem;
            text-align: center;
            letter-spacing: 0.5px;
        }

        .table tbody td {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(45, 90, 39, 0.3);
            color: #ffffff;
            padding: 1rem;
            vertical-align: middle;
        }

        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.12);
            border: 2px solid rgba(45, 90, 39, 0.6);
            border-radius: 8px;
            color: #ffffff;
            padding: 0.6rem 0.75rem;
            transition: all 0.3s ease;
            font-weight: 600;
            font-size: 0.9rem;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.18);
            border-color: rgba(45, 90, 39, 1);
            color: #ffffff;
            box-shadow: 0 0 0 3px rgba(45, 90, 39, 0.4), inset 0 2px 4px rgba(0, 0, 0, 0.3);
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

        .btn {
            border: none;
            border-radius: 12px;
            font-weight: 700;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-size: 0.9rem;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
        }

        .btn-primary {
            background: linear-gradient(135deg, #2d5a27 0%, #3a6b34 100%);
            box-shadow: 0 4px 15px rgba(45, 90, 39, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #3a6b34 0%, #2d5a27 100%);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(45, 90, 39, 0.6);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .btn-success {
            background: linear-gradient(135deg, #2d5a27 0%, #3a6b34 100%);
            box-shadow: 0 4px 15px rgba(45, 90, 39, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #3a6b34 0%, #2d5a27 100%);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(45, 90, 39, 0.6);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #ffffff;
            width: 35px;
            height: 35px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #c82333 0%, #dc3545 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.6);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);
            box-shadow: 0 4px 15px rgba(74, 85, 104, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(74, 85, 104, 0.6);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.95) 0%, rgba(200, 35, 51, 0.95) 100%);
            border: 2px solid rgba(220, 53, 69, 0.8);
            border-radius: 12px;
            color: #ffffff;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
            font-weight: 600;
        }

        .alert-danger ul {
            margin-bottom: 0;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
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

        /* Centrar contenido de celdas */
        .text-center {
            text-align: center !important;
        }

        /* Efecto hover en filas de tabla */
        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: rgba(45, 90, 39, 0.1) !important;
            transform: translateX(5px);
        }
    </style>

    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="row w-100 justify-content-center">
            <div class="col-md-12 col-lg-10">
                <div class="card p-5 position-relative">
                    <h2 class="text-white-enhanced">AGREGAR PROTOTIPOS</h2>
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li class="text-white-enhanced">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('prototipos.storeMultiple') }}" method="POST" id="multiForm">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="prototiposTable">
                                <thead>
                                    <tr>
                                        <th>NOMBRE</th>
                                        <th>ESTADO</th>
                                        <th>PRECIO (BS.)</th>
                                        <th>MOTIVO MANTENIMIENTO</th>
                                        <th>ELIMINAR</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" name="prototipos[0][nombre]" class="form-control" required></td>
                                        <td>
                                            <select name="prototipos[0][estado]" class="form-select estado-select" required>
                                                <option value="1">Para alquilar</option>
                                                <option value="2">Para vender</option>
                                                <option value="3">Mantenimiento</option>
                                            </select>
                                        </td>
                                        <td><input type="number" step="0.01" name="prototipos[0][precio]" class="form-control" required></td>
                                        <td>
                                            <input type="text" name="prototipos[0][observaciones]" class="form-control observaciones-input" style="display:none;" maxlength="250" placeholder="Motivo de mantenimiento">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-sm remove-row" disabled>&times;</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-primary mb-2" id="addRowBtn">AGREGAR OTRO PROTOTIPO</button>
                            <button type="submit" class="btn btn-success mb-2">GUARDAR TODOS</button>
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
            let rowCount = 1;

            function toggleObservaciones(select) {
                const row = select.closest('tr');
                const observaciones = row.querySelector('.observaciones-input');
                if (select.value == '3') {
                    observaciones.style.display = '';
                    observaciones.required = true;
                } else {
                    observaciones.style.display = 'none';
                    observaciones.required = false;
                    observaciones.value = '';
                }
            }

            document.querySelectorAll('.estado-select').forEach(function (select) {
                select.addEventListener('change', function () {
                    toggleObservaciones(this);
                });
                toggleObservaciones(select); // Inicializa
            });

            document.getElementById('addRowBtn').addEventListener('click', function () {
                const table = document.getElementById('prototiposTable').getElementsByTagName('tbody')[0];
                const newRow = table.rows[0].cloneNode(true);
                Array.from(newRow.querySelectorAll('input, select')).forEach(function (input) {
                    input.value = '';
                });
                newRow.querySelectorAll('input, select').forEach(function (input) {
                    const name = input.getAttribute('name');
                    if (name) {
                        input.setAttribute('name', name.replace(/\d+/, rowCount));
                    }
                });
                newRow.querySelector('.remove-row').disabled = false;
                newRow.querySelector('.remove-row').addEventListener('click', function () {
                    newRow.remove();
                });
                // Estado select y observaciones
                newRow.querySelector('.estado-select').addEventListener('change', function () {
                    toggleObservaciones(this);
                });
                toggleObservaciones(newRow.querySelector('.estado-select'));
                table.appendChild(newRow);
                rowCount++;
            });

            document.querySelectorAll('.remove-row').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    if (!btn.disabled) {
                        btn.closest('tr').remove();
                    }
                });
            });
        });
    </script>

</body>

</html>