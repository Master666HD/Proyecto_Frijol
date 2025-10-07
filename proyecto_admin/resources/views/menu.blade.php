<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- The above 4 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!-- Title -->
    <title>FrijolTech</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('img/core-img/favicon.ico') }}">

    <!-- Core Stylesheet -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('/style.css') }}">
</head>

<body>
    <!-- Preloader -->
    <div class="preloader d-flex align-items-center justify-content-center">
        <div class="{{ asset('preloader-circle') }}"></div>
        <div class="{{ asset('preloader-img') }}">
            <img src="{{ asset('img/core-img/leaf.png') }}" alt="">
        </div>
    </div>

    <!-- ##### Header Area Start ##### -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
            <div class="container-fluid">

                <!-- Logo -->
                <a class="navbar-brand d-flex align-items-center" href="{{ route('vistaAdmin') }}">
                    <style>
                        .fontsize {
                            font-size: 40px;
                            padding-left: 50px;
                        }
                    </style>
                    <span class=" fw-bold text-white fontsize">FRIJOL<span class="text-success">TECH</span></span>
                </a>

                <!-- Botón hamburguesa -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                    aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Enlaces -->
                <div class="collapse navbar-collapse justify-content-center fontsize" id="navbarContent">
                    <ul class="navbar-nav gap-4 text-center">
                        <li class="nav-item">
                            <a class="nav-link fs-5 text-white" href="{{ route('vistaAdmin') }}">Menú</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fs-5 text-white" href="{{ route('usuarios.index') }}">Agricultores</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fs-5 text-white" href="{{ route('prototipos.index') }}">Prototipos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fs-5 text-white" href="{{ route('operaciones.index') }}">Operaciones</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fs-5 text-white" href="{{ route('reportes.index') }}">Reportes</a>
                        </li>
                    </ul>
                </div>

                <!-- Botón Cerrar sesión -->
                <form action="{{ route('logout') }}" method="POST" class="d-none d-lg-block">
                    @csrf
                    <button type="submit" class="btn btn-outline-warning rounded-pill px-3 fs-6">
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </nav>
    </header>


    <!-- ##### Header Area End ##### -->

    <!-- ##### Hero Area Start ##### -->



    @yield('contenido')


    <!-- ##### Footer Area Start ##### -->
  <footer class="footer-area bg-img" style="background-image: url('{{ asset('img/bg-img/3.jpg') }}');">
        <!-- Main Footer Area -->
        <div class="main-footer-area">
            <div class="container">
                <div class="row">
                    <!-- Single Footer Widget -->
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="single-footer-widget">
                            <div class=" footer-logo mb-30">
                            <a href="{{route('vistaAdmin')}}"><img src="{{ asset('img/core-img/logo4.png')}}" alt=""></a>
                        </div>
                        <p>Este página está hecho para la administración de los agricultores y proceso de clasificación
                            de frijoles.</p>
                    </div>
                </div>

            </div>
        </div>
    </div>


    </footer>
    <!-- ##### Footer Area End ##### -->

    <!-- ##### All Javascript Files ##### -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <!-- jQuery-2.2.4 js -->
    <script src=" {{ asset('js/jquery/jquery-2.2.4.min.js') }}"></script>
    <!-- Popper js -->
    <script src="{{ asset('js/bootstrap/popper.min.js') }}"></script>
    <!-- Bootstrap js -->
    <script src=" {{ asset('js/bootstrap/bootstrap.min.js') }}"></script>
    <!-- All Plugins js -->
    <script src="{{ asset('js/plugins/plugins.js') }}"></script>
    <!-- Active js -->
    <script src="{{ asset('js/active.js') }}"></script>
</body>

</html>