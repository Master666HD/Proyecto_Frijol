@extends('menu')

@section('contenido')
    <style>
        body {
            background-color: #121212;
            color: #e0e0e0;
            font-family: 'Poppins', sans-serif;
        }

        h1,
        h5 {
            color: #00ff88;
        }

        .card {
            background: rgba(30, 30, 30, 0.9);
            border: 1px solid #00ff88;
            border-radius: 10px;
            color: #fff;
            box-shadow: 0 4px 10px rgba(0, 255, 136, 0.2);
        }

        .card-header {
            background: rgba(0, 255, 136, 0.1);
            border-bottom: 1px solid #00ff88;
            font-weight: bold;
            color: #00ff88;
        }

        table {
            color: #fff;
        }

        thead {
            background-color: rgba(0, 255, 136, 0.2);
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(255, 255, 255, 0.05);
        }

        .card h3 {
            font-size: 2rem;
            margin-top: 10px;
        }
    </style>

    <div class="container mt-4">

        {{-- KPIs principales --}}
        <div class="row text-center mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Total Operaciones</h5>
                        <h3 class="text-light">{{ $totalOperaciones }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Ventas</h5>
                        <h3 style="color:#00ff88;">{{ $totalVentas }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Alquileres</h5>
                        <h3 style="color:#00bfff;">{{ $totalAlquileres }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Ingresos Totales</h5>
                        <h3 class="text-warning">Bs {{ number_format($ingresosTotales, 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- Gráficos --}}
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm mb-4" style="height: 450px;">
                    <div class="card-header fs-5">Maquinas Ocupadas o Vendidas</div>
                    <div class="card-body">
                        <canvas id="prototiposChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm mb-4" style="height: 450px;">
                    <div class="card-header fs-5">Operaciones por Mes</div>
                    <div class="card-body">
                        <canvas id="operacionesChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow-sm mb-4" style="height: 450px;">
                        <div class="card-header fs-5">Distribución de Máquinas (Disponibles)</div>
                        <div class="card-body">
                            <canvas id="prototiposDisponiblesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4" style="background-color: #1b1f24; color: #e0e0e0;">
            <div class="card-header d-flex justify-content-between align-items-center fs-5">
                Últimas Operaciones
            </div>
            <div>
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-success">
                        <tr>
                            <th>#</th>
                            <th>Agricultor</th>
                            <th>Máquina</th>
                            <th>Tipo</th>
                            <th>Precio (Bs)</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ultimasOperaciones as $index => $op)
                            <tr style="transition: background-color 0.3s ease;">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $op->usuario->nombres ?? 'N/A' }}</td>
                                <td>{{ $op->prototipo->nombre ?? 'N/A' }}</td>
                                <td>
                                    @if($op->tipoOperacion == 'VENTA')
                                        <span class="badge bg-success text-light">Venta</span>
                                    @elseif($op->tipoOperacion == 'ALQUILER')
                                        <span class="badge bg-primary text-light">Alquiler</span>
                                    @else
                                        <span class="badge bg-secondary text-light">{{ ucfirst($op->tipoOperacion) }}</span>
                                    @endif
                                </td>
                                <td>Bs {{ number_format($op->precio, 2) }}</td>
                                <td>{{ \Carbon\Carbon::parse($op->fechaRegistro)->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <style>
            .table-hover tbody tr:hover {
                background-color: rgba(40, 167, 69, 0.15) !important;
                cursor: pointer;
            }

            .badge {
                font-size: 0.85rem;
                padding: 0.4em 0.7em;
                border-radius: 0.4em;
            }
        </style>



    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // 🎯 Gráfico Prototipos Disponibles
        new Chart(document.getElementById('prototiposDisponiblesChart'), {
            type: 'doughnut',
            data: {
                labels: ['Para Alquilar', 'Para Vender', 'Mantenimiento'],
                datasets: [{
                    data: [{{ $prototiposParaAlquilar }}, {{ $prototiposParaVender }}, {{ $prototiposMantenimiento }}],
                    backgroundColor: ['#1f77b4', '#00ff88', '#ffc107'],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '65%',  // hace la dona más elegante
                maintainAspectRatio: false,
                aspectRatio: 1.3,
                plugins: {
                    legend: { position: 'bottom', labels: { color: '#e6edf3', font: { size: 13 } } }
                }
            }
        });

        // 🎯 Gráfico Prototipos Ocupados/Vendidos
        new Chart(document.getElementById('prototiposChart'), {
            type: 'doughnut',
            data: {
                labels: ['Vendidos', 'Alquilados'],
                datasets: [{
                    data: [{{ $prototiposVendidos }}, {{ $prototiposAlquilados }}],
                    backgroundColor: ['#00ff88', '#ff4d4d'],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '65%',
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: '#e6edf3', font: { size: 13 } }
                    }
                }
            }
        });


        // 🎯 Gráfico de Operaciones por Mes
        new Chart(document.getElementById('operacionesChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($labelsMeses) !!},
                datasets: [
                    {
                        label: 'Ventas',
                        backgroundColor: '#00ff88',
                        borderRadius: 6, // bordes redondeados en las barras
                        data: {!! json_encode($dataVentas) !!}
                    },
                    {
                        label: 'Alquileres',
                        backgroundColor: '#1f77b4',
                        borderRadius: 6,
                        data: {!! json_encode($dataAlquileres) !!}
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { labels: { color: '#e6edf3', font: { size: 13 } } }
                },
                scales: {
                    x: {
                        ticks: { color: '#e6edf3' },
                        grid: { color: '#333' }
                    },
                    y: {
                        ticks: {
                            color: '#e6edf3',
                            stepSize: 1  // 👈 hace que las unidades sean de 1 en 1
                        },
                        grid: { color: '#333' },
                        beginAtZero: true
                    }
                }
            }
        });

    </script>

    <br>
@endsection