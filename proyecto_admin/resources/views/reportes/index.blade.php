@extends('menu')
@section('contenido')
    <style>
        body {
            background-color: #000000 !important;
            color: #e0e0e0;
            font-family: 'Poppins', sans-serif;
        }

        h2,
        h5 {
            color: #00ff88;
        }

        .card {
            background-color: #1b1f24;
            border: 1px solid #00ff88;
            border-radius: 10px;
            color: #fff;
            box-shadow: 0 4px 10px rgba(0, 255, 136, 0.2);
            margin-bottom: 1.5rem;
            padding: 20px;
        }

        .card h5 {
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(0, 255, 136, 0.4);
            color: #fff;
        }

        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            box-shadow: 0 0 8px #00ff88;
        }
    </style>

    <div class="container mt-5">

        <div class="card">
            <h5>Reporte de Facturación por Alquiler</h5>
            <form action="{{ route('reportes.alquiler') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col">
                        <label>Fecha Inicio</label>
                        <input type="date" name="inicio" class="form-control">
                    </div>
                    <div class="col">
                        <label>Fecha Fin</label>
                        <input type="date" name="fin" class="form-control">
                    </div>
                    <div class="col d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Generar PDF</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="card">
            <h5>Reporte de Facturación por Venta</h5>
            <form action="{{ route('reportes.venta') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col">
                        <label>Fecha Inicio</label>
                        <input type="date" name="inicio" class="form-control">
                    </div>
                    <div class="col">
                        <label>Fecha Fin</label>
                        <input type="date" name="fin" class="form-control">
                    </div>
                    <div class="col d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">Generar PDF</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="card">
            <h5>Reporte de Prototipos en Mantenimiento</h5>
            <form action="{{ route('reportes.mantenimiento') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col">
                        <button type="submit" class="btn btn-warning w-25">Generar PDF</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="card">
            <h5>Reporte de Stock de Prototipos</h5>
            <form action="{{ route('reportes.stock') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col">
                        <button type="submit" class="btn btn-info w-25">Generar PDF</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection