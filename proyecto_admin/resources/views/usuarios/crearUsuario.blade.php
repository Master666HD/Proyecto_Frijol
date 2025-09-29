@extends('menu')

@section('contenido')
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
                    <h2 class="text-center mb-4">Crear Usuario</h2>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('usuarios.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nombres" class="form-label">Nombres:</label>
                            <input type="text" id="nombres" name="nombres" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="apellidos" class="form-label">Apellidos:</label>
                            <input type="text" id="apellidos" name="apellidos" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo:</label>
                            <input type="email" id="correo" name="correo" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="rol" class="form-label">Rol:</label>
                            <select id="rol" name="rol" class="form-select" required>
                                <option value="Admin">Administrador</option>
                                <option value="Agricultor">Agricultor</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono:</label>
                            <input type="number" id="telefono" name="telefono" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="usuario" class="form-label">Usuario:</label>
                            <input type="text" id="usuario" name="usuario" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="contrasenia" class="form-label">Contraseña:</label>
                            <input type="password" id="contrasenia" name="contrasenia" class="form-control" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success mb-2">Crear Usuario</button>
                            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Regresar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
 <script src="{{ asset('js/bootstrap.bundle.min.js') }}" defer></script>

@endsection