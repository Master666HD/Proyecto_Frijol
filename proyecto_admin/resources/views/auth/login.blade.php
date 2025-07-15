<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>

<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
<body>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card p-4">
                    <div class="card-header text-center py-3">
                        <h2 class="mb-0">Iniciar Sesión</h2>
                    </div>
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger text-center mb-3" role="alert">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success text-center mb-3" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ url('/login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="usuario" class="form-label">Usuario:</label>
                                <input type="text" class="form-control" id="usuario" name="usuario" required autocomplete="username">
                            </div>

                            <div class="mb-4">
                                <label for="contrasenia" class="form-label">Contraseña:</label>
                                <input type="password" class="form-control" id="contrasenia" name="contrasenia" required autocomplete="current-password">
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">Iniciar Sesión</button>
                            </div>
                        </form>

                        <p class="text-center mt-4 mb-0">¿No tienes una cuenta? <a href="{{ url('/register') }}">Regístrate aquí</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

 <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
