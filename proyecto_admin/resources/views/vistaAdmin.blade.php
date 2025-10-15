@extends('menu')

@section('contenido')
    <style>
        body {
            background-color: #f9fafb;
            color: #333;
            font-family: 'Poppins', sans-serif;
        }

        h1, h5 {
            color: #2e7d32; /* Verde agrícola */
        }

        .dashboard-title {
            text-align: center;
            margin-bottom: 2rem;
        }

        .dashboard-title h1 {
            font-weight: 700;
            color: #2e7d32;
        }

        .dashboard-title p {
            color: #666;
        }

        /* Cards */
        .card {
            background: #ffffff;
            border: 1px solid #e0f2f1;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(46, 125, 50, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(46, 125, 50, 0.15);
        }

        .card-header {
            background: linear-gradient(90deg, #66bb6a 0%, #a5d6a7 100%);
            color: #1b5e20;
            font-weight: 600;
            border-radius: 16px 16px 0 0;
        }

        /* KPI Cards */
        .kpi-card .card-body {
            padding: 1.5rem;
        }

        .kpi-card h5 {
            color: #4e342e;
            font-weight: 600;
        }

        .kpi-card h3 {
            font-size: 2rem;
            margin-top: 8px;
            font-weight: 700;
        }

        .kpi-icon {
            font-size: 2.5rem;
            color: #2e7d32;
            margin-bottom: 0.5rem;
        }

        /* Table */
        table {
            color: #333;
            border-radius: 10px;
            overflow: hidden;
        }

        thead {
            background-color: #66bb6a; /* Verde medio */
            color: #fff;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f1f8e9; /* Verde muy claro */
        }

        .table-hover tbody tr:hover {
            background-color: #c8e6c9 !important;
            cursor: pointer;
        }

        .badge {
            font-size: 0.85rem;
            padding: 0.4em 0.7em;
            border-radius: 0.4em;
        }

        .badge.bg-success {
            background-color: #43a047 !important;
        }

        .badge.bg-primary {
            background-color: #0288d1 !important;
        }

        .badge.bg-warning {
            background-color: #fbc02d !important;
        }

        /* Charts */
        canvas {
            max-height: 320px;
        }

        /* Animación de entrada */
        .fade-in {
            animation: fadeIn 0.8s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <div class="container mt-4 fade-in">

        {{-- Título --}}
        <div class="dashboard-title">
            <h1>🌾 Panel de Control Agrícola</h1>
            <p>Resumen de operaciones, ventas y estado de las máquinas</p>
        </div>

        {{-- KPIs principales --}}
        <div class="row text-center mb-4">
            <div class="col-md-3">
                <div class="card kpi-card shadow-sm">
                    <div class="card-body">
                        <div class="kpi-icon">📊</div>
                        <h5>Total Operaciones</h5>
                        <h3 class="text-success">{{ $totalOperaciones }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card kpi-card shadow-sm">
                    <div class="card-body">
                        <div class="kpi-icon">🛒</div>
                        <h5>Ventas</h5>
                        <h3 style="color:#2e7d32;">{{ $totalVentas }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card kpi-card shadow-sm">
                    <div class="card-body">
                        <div class="kpi-icon">🔁</div>
                        <h5>Alquileres</h5>
                        <h3 style="color:#0288d1;">{{ $totalAlquileres }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card kpi-card shadow-sm">
                    <div class="card-body">
                        <div class="kpi-icon">💰</div>
                        <h5>Ingresos Totales</h5>
                        <h3 class="text-warning">Bs {{ number_format($ingresosTotales, 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- Gráficos --}}
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header fs-5">🌱 Máquinas Ocupadas o Vendidas</div>
                    <div class="card-body">
                        <canvas id="prototiposChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header fs-5">📅 Operaciones por Mes</div>
                    <div class="card-body">
                        <canvas id="operacionesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header fs-5">🚜 Distribución de Máquinas Disponibles</div>
                    <div class="card-body">
                        <canvas id="prototiposDisponiblesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabla --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center fs-5">
                🧾 Últimas Operaciones
            </div>
            <div>
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead>
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
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $op->usuario->nombres ?? 'N/A' }}</td>
                                <td>{{ $op->prototipo->nombre ?? 'N/A' }}</td>
                                <td>
                                    @if($op->tipoOperacion == 'VENTA')
                                        <span class="badge bg-success text-light">Venta</span>
                                    @elseif($op->tipoOperacion == 'ALQUILER')
                                        <span class="badge bg-primary text-light">Alquiler</span>
                                    @else
                                        <span class="badge bg-warning text-dark">{{ ucfirst($op->tipoOperacion) }}</span>
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

    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // 🍀 Gráfico Prototipos Disponibles
        new Chart(document.getElementById('prototiposDisponiblesChart'), {
            type: 'doughnut',
            data: {
                labels: ['Para Alquilar', 'Para Vender', 'Mantenimiento'],
                datasets: [{
                    data: [{{ $prototiposParaAlquilar }}, {{ $prototiposParaVender }}, {{ $prototiposMantenimiento }}],
                    backgroundColor: ['#81c784', '#aed581', '#fbc02d'],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '65%',
                plugins: {
                    legend: { position: 'bottom', labels: { color: '#333', font: { size: 13 } } }
                }
            }
        });

        // 🌾 Gráfico Prototipos Ocupados/Vendidos
        new Chart(document.getElementById('prototiposChart'), {
            type: 'doughnut',
            data: {
                labels: ['Vendidos', 'Alquilados'],
                datasets: [{
                    data: [{{ $prototiposVendidos }}, {{ $prototiposAlquilados }}],
                    backgroundColor: ['#43a047', '#4fc3f7'],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '65%',
                plugins: {
                    legend: { position: 'bottom', labels: { color: '#333', font: { size: 13 } } }
                }
            }
        });

        // 📊 Gráfico de Operaciones por Mes
        new Chart(document.getElementById('operacionesChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($labelsMeses) !!},
                datasets: [
                    {
                        label: 'Ventas',
                        backgroundColor: '#66bb6a',
                        borderRadius: 6,
                        data: {!! json_encode($dataVentas) !!}
                    },
                    {
                        label: 'Alquileres',
                        backgroundColor: '#4fc3f7',
                        borderRadius: 6,
                        data: {!! json_encode($dataAlquileres) !!}
                    }
                ]
            },
            options: {
                plugins: {
                    legend: { labels: { color: '#333', font: { size: 13 } } }
                },
                scales: {
                    x: {
                        ticks: { color: '#333' },
                        grid: { color: '#e0e0e0' }
                    },
                    y: {
                        ticks: { color: '#333', stepSize: 1 },
                        grid: { color: '#e0e0e0' },
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
