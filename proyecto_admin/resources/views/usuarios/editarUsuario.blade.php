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
            background-image: url('{{ asset('img/actualizar.jpg') }}');
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
                    <h2 class="text-center mb-4">Editar Usuario</h2>
                    <form action="{{ route('usuarios.update', $usuarios->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="nombres" class="form-label">Nombres:</label>
                            <input type="text" id="nombres" name="nombres" class="form-control" value="{{ $usuarios->nombres }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="apellidos" class="form-label">Apellidos:</label>
                            <input type="text" id="apellidos" name="apellidos" class="form-control" value="{{ $usuarios->apellidos }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo:</label>
                            <input type="email" id="correo" name="correo" class="form-control" value="{{ $usuarios->correo }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="rol" class="form-label">Rol:</label>
                            <select id="rol" name="rol" class="form-select" required>
                                <option value="Admin" {{ $usuarios->rol === 'Admin' ? 'selected' : '' }}>Admin</option>
                                <option value="Agricultor" {{ $usuarios->rol === 'Agricultor' ? 'selected' : '' }}>Agricultor</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono:</label>
                            <input type="text" id="telefono" name="telefono" class="form-control" value="{{ $usuarios->telefono }}">
                        </div>
                        <div class="mb-3">
                            <label for="usuario" class="form-label">Usuario:</label>
                            <input type="text" id="usuario" name="usuario" class="form-control" value="{{ $usuarios->usuario }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="contrasenia" class="form-label">Contraseña:</label>
                            <input type="password" id="contrasenia" name="contrasenia" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="contrasenia_confirmation" class="form-label">Confirmar Contraseña:</label>
                            <input type="password" id="contrasenia_confirmation" name="contrasenia_confirmation" class="form-control">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning mb-2">Actualizar Usuario</button>
                            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Regresar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}" defer></script>

</body>
</html>