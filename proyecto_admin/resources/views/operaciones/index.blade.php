@extends('menu')

@section('contenido')
<style>
    body {
        background-color: #121212;
        color: #e0e0e0;
        font-family: 'Poppins', sans-serif;
    }
    h1, h5 {
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
    .badge {
        font-size: 0.85rem;
    }
</style>

<div class="container mt-4">
    <div class="card shadow-sm mb-4" style="background-color: #1b1f24; color: #e0e0e0;">
        <div class="card-header d-flex justify-content-between align-items-center fs-5">
            Lista de Operaciones
            <a href="{{ route('operaciones.create') }}" class="btn btn-primary">
                <i class="fa fa-plus-circle"></i> Nueva Operación
            </a>
        </div>
        <div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-success">
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
                                        <span class="badge bg-success bg-opacity-75">
                                            Venta
                                        </span>
                                    @else
                                        <span class="badge bg-info bg-opacity-75">
                                            <i class="fa fa-sync-alt"></i> Alquiler
                                        </span>
                                    @endif
                                </td>
                                <td>{{ number_format($op->precio, 2) }} Bs</td>
                           <td>{{ \Carbon\Carbon::parse($op->fechaOperacion)->setTimezone('America/La_Paz')->format('d/m/Y') }}</td>

                                <td>
                                    @if($op->estado == 'ACTIVO')
                                        <span class="badge bg-warning bg-opacity-75">
                                            <i class="fa fa-hourglass-half"></i> Activo
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-75">
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
        <div class="modal-content bg-dark text-light">
            <div class="modal-header border-0">
                <h5 class="modal-title text-danger">
                    <i class="fa fa-exclamation-triangle"></i> Confirmar eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                ¿Seguro que deseas eliminar esta operación?
            </div>
            <div class="modal-footer border-0">
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
