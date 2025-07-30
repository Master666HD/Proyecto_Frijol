@extends('menu')

@section('contenido')
<div class="container mt-4">
    <h1 class="mb-4 ">Panel de Administración</h1>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total de semillas procesadas</h5>
             <p class="card-text text-dark display-4">{{ $total }}</p> 
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Semillas BUENAS</h5>
                   <p class="card-text text-dark display-4">{{ $semillasBuenas }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-danger mb-3">
                <div class="card-body">
                    <h5 class="card-title">Semillas MALAS</h5>
                     <p class="card-text text-dark display-4">{{ $semillasMalas }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Tendencia diaria</div>
                <div class="card-body">
                    <canvas id="tendenciaChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Últimas semillas registradas</div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Color</th>
                                <th>Peso (gr)</th>
                                <th>Tamaño</th>
                                <th>Usuario</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                             @foreach ($ultimasSemillas as $semilla)
                                <tr>
                                    <td>{{ $semilla->color }}</td>
                                    <td>{{ $semilla->peso }}</td>
                                    <td>{{ $semilla->tamano }}</td>
                                    <td>{{ $semilla->usuario->nombres }} {{ $semilla->usuario->apellidos }}</td>
                                    <td>{{ $semilla->fechaRegistro }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/Chart.min.js') }}"></script>
<script>
    const ctx = document.getElementById('tendenciaChart').getContext('2d');
    const tendenciaChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: 'Semillas por día',
                data: {!! json_encode($data) !!},
                fill: false,
                borderColor: 'blue',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

@endsection