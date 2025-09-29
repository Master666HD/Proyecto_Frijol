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

    <div class="card shadow-sm mb-4" style="background-color: #1b1f24; color: #e0e0e0;">
        <div class="card-header d-flex justify-content-between align-items-center fs-5">
            Lista de Operaciones
            <button class="btn btn-primary mb-3" @if(!$prototiposDisponibles) data-bs-toggle="modal"
            data-bs-target="#noDisponibleModal" @else onclick="window.location='{{ route('operaciones.create') }}'"
                @endif>
                Registrar nueva operación
            </button>
        </div>
        <div class="modal fade" id="noDisponibleModal" tabindex="-1" aria-labelledby="noDisponibleLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title" id="noDisponibleLabel">¡Atención!</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <strong>NO TIENES MÁQUINAS DISPONIBLES</strong>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <table class="table table-hover align-middle mb-0">
                <thead class="table-success">
                    <tr>
                        <th>Nro</th>
                        <th>Agricultor</th>
                        <th>Prototipo</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($operaciones as $operacion)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $operacion->usuario->nombres }} {{ $operacion->usuario->apellidos }}</td>
                            <td>{{ $operacion->prototipo->nombre }}</td>
                            <td>{{ $operacion->tipoOperacion }}</td>
                            <td>
                                @if ($operacion->estado == 'ACTIVO')
                                    <span class="badge bg-success">{{ $operacion->estado }}</span>
                                @elseif ($operacion->estado == 'DEVUELTO')
                                    <span class="badge bg-warning text-dark">{{ $operacion->estado }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ $operacion->estado }}</span>
                                @endif
                            </td>
                            <td>{{ $operacion->fechaRegistro }}</td>
                            <td>
                                @if ($operacion->tipoOperacion == 'ALQUILER' && $operacion->estado == 'ACTIVO')
                                    <a href="{{ route('operaciones.devolucion.form', $operacion->id) }}"
                                        class="btn btn-sm btn-warning">
                                        Registrar devolución
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No hay operaciones registradas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection