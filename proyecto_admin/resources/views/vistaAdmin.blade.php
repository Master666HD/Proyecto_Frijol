@extends('menu')

@section('contenido')
    <style>
        body {
            background: linear-gradient(135deg, #0c0c0c 0%, #1a1a1a 50%, #0c0c0c 100%);
            color: #ffffff;
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        .container {
            max-width: 1400px;
        }

        h1, h5 {
            color: #ffffff;
            font-weight: 600;
        }

        .card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            color: #fff;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 60px rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .card-header {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.12) 0%, rgba(255, 255, 255, 0.05) 100%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            font-weight: 600;
            color: #ffffff;
            padding: 1.5rem 2rem;
            font-size: 1.2rem;
            letter-spacing: 0.5px;
        }

        .kpi-card {
            text-align: center;
            padding: 2rem 1.5rem;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .kpi-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.6s ease;
        }

        .kpi-card:hover::before {
            left: 100%;
        }

        .kpi-card h3 {
            font-size: 2.5rem;
            margin-top: 15px;
            font-weight: 700;
            background: linear-gradient(135deg, #ffffff 0%, #e0e0e0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 4px 20px rgba(255, 255, 255, 0.3);
        }

        .kpi-card h5 {
            font-size: 1rem;
            opacity: 0.9;
            letter-spacing: 1px;
        }

        .chart-container {
            position: relative;
            height: 100%;
            padding: 2rem;
        }

        table {
            color: #fff;
            border-radius: 16px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.05);
        }

        thead {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0.08) 100%);
        }

        thead th {
            font-weight: 600;
            letter-spacing: 0.5px;
            padding: 1.2rem 1rem;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(255, 255, 255, 0.03);
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.6em 1em;
            border-radius: 10px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .bg-success {
            background: linear-gradient(135deg, #2d5a27 0%, #3a6b34 100%) !important;
            box-shadow: 0 4px 15px rgba(45, 90, 39, 0.3);
        }

        .bg-primary {
            background: linear-gradient(135deg, #2c5282 0%, #2a4365 100%) !important;
            box-shadow: 0 4px 15px rgba(44, 82, 130, 0.3);
        }

        .table-hover tbody tr {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .table-hover tbody tr:hover {
            background: rgba(255, 255, 255, 0.1) !important;
            transform: translateX(8px);
            border-left: 4px solid #ffffff;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(255, 255, 255, 0.1);
        }

        .table td {
            padding: 1.2rem 1rem;
            vertical-align: middle;
            border-color: rgba(255, 255, 255, 0.1);
        }

        /* Efectos de partículas decorativas */
        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: -1;
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
    </style>

    <div class="container mt-5">

        {{-- KPIs principales --}}
        <div class="row text-center mb-5">
            <div class="col-md-3 mb-4">
                <div class="card shadow-lg kpi-card">
                    <div class="card-body">
                        <h5 class="mb-3">TOTAL OPERACIONES</h5>
                        <h3>{{ $totalOperaciones }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card shadow-lg kpi-card">
                    <div class="card-body">
                        <h5 class="mb-3">VENTAS</h5>
                        <h3 style="background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">{{ $totalVentas }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card shadow-lg kpi-card">
                    <div class="card-body">
                        <h5 class="mb-3">ALQUILERES</h5>
                        <h3 style="background: linear-gradient(135deg, #2196F3 0%, #0D47A1 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">{{ $totalAlquileres }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card shadow-lg kpi-card">
                    <div class="card-body">
                        <h5 class="mb-3">INGRESOS TOTALES</h5>
                        <h3 style="background: linear-gradient(135deg, #FFD700 0%, #FFA000 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Bs {{ number_format($ingresosTotales, 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- Gráficos --}}
        <div class="row mb-5">
            <div class="col-md-6 mb-4">
                <div class="card shadow-lg h-100">
                    <div class="card-header d-flex align-items-center">
                        <i class="fas fa-chart-pie me-2"></i>
                        MÁQUINAS OCUPADAS O VENDIDAS
                    </div>
                    <div class="chart-container">
                        <canvas id="prototiposChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card shadow-lg h-100">
                    <div class="card-header d-flex align-items-center">
                        <i class="fas fa-chart-bar me-2"></i>
                        OPERACIONES POR MES
                    </div>
                    <div class="chart-container">
                        <canvas id="operacionesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center mb-5">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header d-flex align-items-center">
                        <i class="fas fa-chart-donut me-2"></i>
                        DISTRIBUCIÓN DE MÁQUINAS DISPONIBLES
                    </div>
                    <div class="chart-container">
                        <canvas id="prototiposDisponiblesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabla de últimas operaciones --}}
        <div class="card shadow-lg mb-5">
            <div class="card-header fs-5 d-flex align-items-center">
                <i class="fas fa-list-alt me-2"></i>
                ÚLTIMAS OPERACIONES
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>AGRICULTOR</th>
                            <th>MÁQUINA</th>
                            <th>TIPO</th>
                            <th>PRECIO (BS)</th>
                            <th>FECHA</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ultimasOperaciones as $index => $op)
                            <tr>
                                <td class="fw-bold text-white">{{ $index + 1 }}</td>
                                <td class="text-white">{{ $op->usuario->nombres ?? 'N/A' }}</td>
                                <td class="text-white">{{ $op->prototipo->nombre ?? 'N/A' }}</td>
                                <td>
                                    @if($op->tipoOperacion == 'VENTA')
                                        <span class="badge bg-success">
                                            <i class="fas fa-shopping-cart me-1"></i>VENTA
                                        </span>
                                    @elseif($op->tipoOperacion == 'ALQUILER')
                                        <span class="badge bg-primary">
                                            <i class="fas fa-handshake me-1"></i>ALQUILER
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($op->tipoOperacion) }}</span>
                                    @endif
                                </td>
                                <td class="fw-semibold text-white">Bs {{ number_format($op->precio, 2) }}</td>
                                <td class="text-white">{{ \Carbon\Carbon::parse($op->fechaRegistro)->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <script>
        // Configuración global de Chart.js
        Chart.defaults.color = '#ffffff';
        Chart.defaults.font.family = 'Inter, sans-serif';
        Chart.defaults.font.weight = '500';

        // Colores personalizados
        const colors = {
            verdeOscuro: ['#2E7D32', '#4CAF50', '#66BB6A', '#81C784'],
            azul: ['#1565C0', '#1976D2', '#1E88E5', '#2196F3'],
            amarillo: ['#FFA000', '#FFB300', '#FFC107', '#FFCA28'],
            gris: ['#424242', '#616161', '#757575', '#9E9E9E'],
            blanco: ['#FFFFFF', '#F5F5F5', '#EEEEEE', '#E0E0E0']
        };

        // 🎯 Gráfico Prototipos Disponibles (Dona premium)
        new Chart(document.getElementById('prototiposDisponiblesChart'), {
            type: 'doughnut',
            data: {
                labels: ['Para Alquilar', 'Para Vender', 'Mantenimiento'],
                datasets: [{
                    data: [{{ $prototiposParaAlquilar }}, {{ $prototiposParaVender }}, {{ $prototiposMantenimiento }}],
                    backgroundColor: [
                        colors.azul[1],
                        colors.verdeOscuro[1],
                        colors.amarillo[0]
                    ],
                    borderColor: colors.blanco[0],
                    borderWidth: 3,
                    hoverBackgroundColor: [
                        colors.azul[0],
                        colors.verdeOscuro[0],
                        colors.amarillo[1]
                    ],
                    hoverBorderColor: colors.blanco[0],
                    hoverBorderWidth: 4,
                    hoverOffset: 20
                }]
            },
            options: {
                cutout: '65%',
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        position: 'bottom', 
                        labels: { 
                            color: colors.blanco[0],
                            font: { size: 14, weight: '600' },
                            padding: 25,
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.9)',
                        titleColor: colors.blanco[0],
                        bodyColor: colors.blanco[0],
                        borderColor: colors.blanco[0],
                        borderWidth: 1,
                        padding: 15,
                        boxPadding: 10,
                        usePointStyle: true
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true,
                    duration: 2000,
                    easing: 'easeOutQuart'
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
                    backgroundColor: [
                        colors.verdeOscuro[1],
                        colors.azul[1]
                    ],
                    borderColor: colors.blanco[0],
                    borderWidth: 3,
                    hoverBackgroundColor: [
                        colors.verdeOscuro[0],
                        colors.azul[0]
                    ],
                    hoverBorderColor: colors.blanco[0],
                    hoverBorderWidth: 4,
                    hoverOffset: 20
                }]
            },
            options: {
                cutout: '65%',
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        position: 'bottom', 
                        labels: { 
                            color: colors.blanco[0],
                            font: { size: 14, weight: '600' },
                            padding: 25,
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.9)',
                        titleColor: colors.blanco[0],
                        bodyColor: colors.blanco[0],
                        borderColor: colors.blanco[0],
                        borderWidth: 1,
                        padding: 15
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true,
                    duration: 2000
                }
            }
        });

        // 🎯 Gráfico de Operaciones por Mes (Barras premium)
        new Chart(document.getElementById('operacionesChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($labelsMeses) !!},
                datasets: [
                    {
                        label: 'Ventas',
                        backgroundColor: colors.verdeOscuro[1],
                        borderColor: colors.verdeOscuro[0],
                        borderWidth: 2,
                        borderRadius: 12,
                        borderSkipped: false,
                        data: {!! json_encode($dataVentas) !!},
                        hoverBackgroundColor: colors.verdeOscuro[0],
                        hoverBorderColor: colors.blanco[0],
                        hoverBorderWidth: 3
                    },
                    {
                        label: 'Alquileres',
                        backgroundColor: colors.azul[1],
                        borderColor: colors.azul[0],
                        borderWidth: 2,
                        borderRadius: 12,
                        borderSkipped: false,
                        data: {!! json_encode($dataAlquileres) !!},
                        hoverBackgroundColor: colors.azul[0],
                        hoverBorderColor: colors.blanco[0],
                        hoverBorderWidth: 3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        labels: { 
                            color: colors.blanco[0],
                            font: { size: 14, weight: '600' }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.9)',
                        titleColor: colors.blanco[0],
                        bodyColor: colors.blanco[0],
                        borderColor: colors.blanco[0],
                        borderWidth: 1,
                        padding: 15
                    }
                },
                scales: {
                    x: {
                        ticks: { 
                            color: colors.blanco[0],
                            font: { weight: '600' }
                        },
                        grid: { 
                            color: 'rgba(255, 255, 255, 0.1)',
                            drawBorder: false
                        }
                    },
                    y: {
                        ticks: {
                            color: colors.blanco[0],
                            stepSize: 1,
                            font: { weight: '600' }
                        },
                        grid: { 
                            color: 'rgba(255, 255, 255, 0.1)',
                            drawBorder: false
                        },
                        beginAtZero: true
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeOutQuart'
                }
            }
        });

        // Efecto de partículas decorativas
        function createParticles() {
            const container = document.querySelector('.container');
            for (let i = 0; i < 15; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.width = Math.random() * 4 + 2 + 'px';
                particle.style.height = particle.style.width;
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 5 + 's';
                particle.style.opacity = Math.random() * 0.3 + 0.1;
                container.appendChild(particle);
            }
        }

        createParticles();
    </script>

    <br>
@endsection