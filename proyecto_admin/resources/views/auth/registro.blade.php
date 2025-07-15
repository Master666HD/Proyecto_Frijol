<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>

<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/registro.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8"> <div class="card p-4">
                    <div class="card-header text-center py-3">
                        <h2 class="mb-0">Registro de Usuario</h2>
                    </div>
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger text-center mb-3" role="alert">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ url('/register') }}">
                            @csrf
                            <div class="row"> <div class="col-md-6 mb-3">
                                    <label for="nombres" class="form-label">Nombres:</label>
                                    <input type="text" class="form-control" id="nombres" name="nombres" value="{{ old('nombres') }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="apellidos" class="form-label">Apellidos:</label>
                                    <input type="text" class="form-control" id="apellidos" name="apellidos" value="{{ old('apellidos') }}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="telefono" class="form-label">Teléfono:</label>
                                    <input type="text" class="form-control" id="telefono" name="telefono" value="{{ old('telefono') }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="correo" class="form-label">Correo:</label>
                                    <input type="email" class="form-control" id="correo" name="correo" value="{{ old('correo') }}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="usuario" class="form-label">Usuario:</label>
                                    <input type="text" class="form-control" id="usuario" name="usuario" value="{{ old('usuario') }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="contrasenia" class="form-label">Contraseña:</label>
                                    <input type="password" class="form-control" id="contrasenia" name="contrasenia" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 offset-md-3 mb-4"> <label for="rol" class="form-label">Rol:</label>
                                    <select class="form-select" id="rol" name="rol" required>
                                        <option value="Agricultor" {{ old('rol') == 'Agricultor' ? 'selected' : '' }}>Agricultor</option>
                                        <option value="Admin" {{ old('rol') == 'Admin' ? 'selected' : '' }}>Administrador</option>
                                    </select>
                                </div>
                                </div>

                            <div class="d-grid gap-2 mt-3"> <button type="submit" class="btn btn-primary btn-lg">Registrar</button>
                            </div>
                        </form>

                        <p class="text-center mt-4 mb-0">¿Ya tienes una cuenta? <a href="{{ url('/login') }}">Inicia sesión aquí</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

 <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script></body>
</html>