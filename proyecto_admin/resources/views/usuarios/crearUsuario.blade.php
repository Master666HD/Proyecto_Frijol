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

        .btn-success {
            background: linear-gradient(135deg, #2d5a27 0%, #3a6b34 100%);
            box-shadow: 0 6px 20px rgba(45, 90, 39, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-success:hover {
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

        /* Mejorar el contraste del card */
        .card {
            background: linear-gradient(135deg, rgba(30, 30, 30, 0.95) 0%, rgba(40, 40, 40, 0.95) 100%);
        }
    </style>

    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="row w-100 justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card p-5 position-relative">
                    <h2 class="text-white-enhanced">CREAR USUARIO</h2>
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li class="text-white-enhanced">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('usuarios.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombres" class="form-label text-white-enhanced">Nombres:</label>
                                <input type="text" id="nombres" name="nombres" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="apellidos" class="form-label text-white-enhanced">Apellidos:</label>
                                <input type="text" id="apellidos" name="apellidos" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="correo" class="form-label text-white-enhanced">Correo:</label>
                            <input type="email" id="correo" name="correo" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="rol" class="form-label text-white-enhanced">Rol:</label>
                            <select id="rol" name="rol" class="form-select" required>
                                <option value="Admin">Administrador</option>
                                <option value="Agricultor">Agricultor</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label text-white-enhanced">Teléfono:</label>
                            <input type="number" id="telefono" name="telefono" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="usuario" class="form-label text-white-enhanced">Usuario:</label>
                            <input type="text" id="usuario" name="usuario" class="form-control" required>
                        </div>
                        <div class="mb-4">
                            <label for="contrasenia" class="form-label text-white-enhanced">Contraseña:</label>
                            <input type="password" id="contrasenia" name="contrasenia" class="form-control" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">CREAR USUARIO</button>
                            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">REGRESAR</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}" defer></script>
</body>
</html>