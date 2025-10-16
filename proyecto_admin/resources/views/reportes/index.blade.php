@extends('menu')
@section('contenido')
    <style>
        body {
            background: linear-gradient(135deg, #0c0c0c 0%, #1a1a1a 50%, #0c0c0c 100%);
            color: #ffffff;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            padding: 2rem 0;
        }

        h2, h5 {
            color: #ffffff;
            font-weight: 600;
        }

        .card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(45, 90, 39, 0.4);
            border-radius: 20px;
            color: #fff;
            box-shadow: 0 8px 32px rgba(45, 90, 39, 0.2);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            margin-bottom: 2rem;
            padding: 2rem;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 60px rgba(45, 90, 39, 0.3);
            border-color: rgba(45, 90, 39, 0.6);
        }

        .card h5 {
            font-weight: 600;
            margin-bottom: 1.5rem;
            font-size: 1.3rem;
            letter-spacing: 0.5px;
            background: linear-gradient(135deg, #ffffff 0%, #e0e0e0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(45, 90, 39, 0.4);
            border-radius: 12px;
            color: #ffffff;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(45, 90, 39, 0.8);
            color: #ffffff;
            box-shadow: 0 0 0 3px rgba(45, 90, 39, 0.3);
            transform: translateY(-2px);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        label {
            color: #ffffff;
            font-weight: 600;
            margin-bottom: 0.5rem;
            letter-spacing: 0.5px;
        }


        .btn-primary {
            background: linear-gradient(135deg, #2d5a27 0%, #3a6b34 100%);
            box-shadow: 0 4px 15px rgba(45, 90, 39, 0.4);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #3a6b34 0%, #2d5a27 100%);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(45, 90, 39, 0.6);
        }

        .btn-success {
            background: linear-gradient(135deg, #2d5a27 0%, #3a6b34 100%);
            box-shadow: 0 4px 15px rgba(45, 90, 39, 0.4);
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #3a6b34 0%, #2d5a27 100%);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(45, 90, 39, 0.6);
        }


        .btn-warning:hover {
            background: linear-gradient(135deg, #FF8C00 0%, #FFA000 100%);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 160, 0, 0.6);
            color: #ffffff;
        }

        .btn-info {
            background: linear-gradient(135deg, #0dcaf0 0%, #0aa2c0 100%);
            box-shadow: 0 4px 15px rgba(13, 202, 240, 0.4);
            color: #ffffff;
        }

        .btn-info:hover {
            background: linear-gradient(135deg, #0aa2c0 0%, #0dcaf0 100%);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(13, 202, 240, 0.6);
            color: #ffffff;
        }

        .row {
            align-items: center;
        }

        .d-flex.align-items-end {
            padding-bottom: 0.5rem;
        }

        /* Iconos decorativos */
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #2d5a27, #3a6b34, #2d5a27);
            border-radius: 20px 20px 0 0;
        }

        /* Efectos de hover en inputs */
        .form-control:hover {
            border-color: rgba(45, 90, 39, 0.6);
            transform: translateY(-1px);
        }
    </style>

    <div class="container mt-5">
        <div class="card">
            <h5><i class="fas fa-file-invoice-dollar me-2"></i>Reporte de Facturación por Alquiler</h5>
            <form action="{{ route('reportes.alquiler') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <label>Fecha Inicio</label>
                        <input type="date" name="inicio" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label>Fecha Fin</label>
                        <input type="date" name="fin" class="form-control">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-download me-2"></i>Generar PDF
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="card">
            <h5><i class="fas fa-shopping-cart me-2"></i>Reporte de Facturación por Venta</h5>
            <form action="{{ route('reportes.venta') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <label>Fecha Inicio</label>
                        <input type="date" name="inicio" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label>Fecha Fin</label>
                        <input type="date" name="fin" class="form-control">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-download me-2"></i>Generar PDF
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="card">
            <h5><i class="fas fa-tools me-2"></i>Reporte de Prototipos en Mantenimiento</h5>
            <form action="{{ route('reportes.mantenimiento') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-download me-2"></i>Generar PDF
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="card">
            <h5><i class="fas fa-boxes me-2"></i>Reporte de Stock de Prototipos</h5>
            <form action="{{ route('reportes.stock') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-info">
                            <i class="fas fa-download me-2"></i>Generar PDF
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    @endpush
@endsection