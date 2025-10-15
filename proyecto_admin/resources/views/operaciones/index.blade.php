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
        color: #333;
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
        border-bottom: 1px solid #e0f2f1;
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

    .badge.bg-info {
        background-color: #0288d1 !important;
    }

    .badge.bg-warning {
        background-color: #fbc02d !important;
        color: #333 !important;
    }

    .badge.bg-secondary {
        background-color: #78909c !important;
    }

    /* Botones */
    .btn-primary {
        background: linear-gradient(135deg, #2e7d32 0%, #66bb6a 100%);
        border: none;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
    }

    .btn-warning {
        background: linear-gradient(135deg, #fbc02d 0%, #fdd835 100%);
        border: none;
        border-radius: 6px;
        color: #333;
    }

    .btn-info {
        background: linear-gradient(135deg, #0288d1 0%, #4fc3f7 100%);
        border: none;
        border-radius: 6px;
        color: white;
    }

    .btn-danger {
        background: linear-gradient(135deg, #d32f2f 0%, #f44336 100%);
        border: none;
        border-radius: 6px;
    }

    /* Animación de entrada */
    .fade-in {
        animation: fadeIn 0.8s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Modal */
    .modal-content {
        border-radius: 16px;
        border: none;
    }

    .modal-header {
        border-bottom: 1px solid #e0f2f1;
        background: #f9fafb;
        border-radius: 16px 16px 0 0;
    }

    .modal-footer {
        border-top: 1px solid #e0f2f1;
        background: #f9fafb;
        border-radius: 0 0 16px 16px;
    }
</style>

<div class="container mt-4 fade-in">

    {{-- Título --}}
    <div class="dashboard-title">
        <h1>🌾 Gestión de Operaciones Agrícolas</h1>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center fs-5">
            🧾 Lista de Operaciones
            <a href="{{ route('operaciones.create') }}" class="btn btn-primary">
                <i class="fa fa-plus-circle"></i> Nueva Operación
            </a>
        </div>
        <div>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th scope="col">Nro</th>
                            <th scope="col">Usuario</th>
                            <th scope="col">Prototipo</th>
                            <th scope="col">Tipo</th>
                            <th scope="col">Precio</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Estado</th>
                            <th scope="col" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $contador = 1; @endphp
                        @foreach($operaciones as $op)
                            <tr>
                                <td>{{ $contador++ }}</td>
                                <td>{{ $op->usuario->nombres }} {{ $op->usuario->apellidos }}</td>
                                <td>{{ $op->prototipo->nombre }}</td>
                                <td>
                                    @if($op->tipoOperacion == 'VENTA')
                                        <span class="badge bg-success">
                                            <i class="fa fa-shopping-cart"></i> Venta
                                        </span>
                                    @else
                                        <span class="badge bg-info">
                                            <i class="fa fa-sync-alt"></i> Alquiler
                                        </span>
                                    @endif
                                </td>
                                <td>{{ number_format($op->precio, 2) }} Bs</td>
                                <td>{{ \Carbon\Carbon::parse($op->fechaOperacion)->setTimezone('America/La_Paz')->format('d/m/Y') }}</td>

                                <td>
                                    @if($op->estado == 'ACTIVO')
                                        <span class="badge bg-warning">
                                            <i class="fa fa-hourglass-half"></i> Activo
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="fa fa-check-circle"></i> Finalizado
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('operaciones.edit', $op->id) }}" 
                                       class="btn btn-sm btn-warning me-1" 
                                       title="Editar">
                                        <i class="fa fa-edit"></i>
                                    </a>

                                    {{-- Si es alquiler activo, mostrar botón de devolución --}}
                                    @if($op->tipoOperacion == 'ALQUILER' && $op->estado == 'ACTIVO')
                                        <a href="{{ route('operaciones.devolucion.form', $op->id) }}" 
                                           class="btn btn-sm btn-info me-1" 
                                           title="Registrar Devolución">
                                            <i class="fa fa-undo"></i>
                                        </a>
                                    @endif

                                    <form action="{{ route('operaciones.destroy', $op->id) }}" 
                                          method="POST" style="display:inline-block;" 
                                          class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                class="btn btn-sm btn-danger delete-btn" 
                                                data-id="{{ $op->id }}" 
                                                title="Eliminar">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal de confirmación --}}
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    <i class="fa fa-exclamation-triangle"></i> Confirmar eliminación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                ¿Seguro que deseas eliminar esta operación?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Eliminar</button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
@endpush

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let formToSubmit = null;
        let modalInstance = null;

        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                formToSubmit = this.closest('form');
                modalInstance = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
                modalInstance.show();
            });
        });

        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if(formToSubmit) formToSubmit.submit();
            if(modalInstance) modalInstance.hide();
        });
    });
</script>
@endsection