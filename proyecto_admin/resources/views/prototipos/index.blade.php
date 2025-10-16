{{-- filepath: resources/views/prototipos/index.blade.php --}}
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

    .card {
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(45, 90, 39, 0.4);
        border-radius: 20px;
        color: #fff;
        box-shadow: 0 8px 32px rgba(45, 90, 39, 0.2);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 60px rgba(45, 90, 39, 0.3);
        border-color: rgba(45, 90, 39, 0.6);
    }

    .card-header {
        background: linear-gradient(135deg, rgba(45, 90, 39, 0.2) 0%, rgba(58, 107, 52, 0.1) 100%);
        border-bottom: 1px solid rgba(45, 90, 39, 0.4);
        font-weight: 600;
        color: #ffffff;
        padding: 1.5rem 2rem;
        font-size: 1.2rem;
        letter-spacing: 0.5px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #2d5a27 0%, #3a6b34 100%);
        border: 1px solid rgba(45, 90, 39, 0.4);
        border-radius: 10px;
        color: #ffffff;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #3a6b34 0%, #2d5a27 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(45, 90, 39, 0.5);
        border-color: rgba(45, 90, 39, 0.6);
        color: #ffffff;
    }

    .table-responsive {
        border-radius: 16px;
        overflow: hidden;
    }

    table {
        color: #ffffff;
        margin: 0;
    }

    thead {
        background: linear-gradient(135deg, rgba(45, 90, 39, 0.2) 0%, rgba(58, 107, 52, 0.1) 100%);
    }

    thead th {
        font-weight: 600;
        letter-spacing: 0.5px;
        padding: 1.2rem 1rem;
        color: #ffffff;
        border-bottom: 2px solid rgba(45, 90, 39, 0.4);
    }

    tbody td {
        padding: 1.2rem 1rem;
        vertical-align: middle;
        border-color: rgba(45, 90, 39, 0.2);
        color: #ffffff;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(45, 90, 39, 0.05);
    }

    .table-hover tbody tr {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }

    .table-hover tbody tr:hover {
        background: rgba(45, 90, 39, 0.15) !important;
        transform: translateX(8px);
        border-left: 4px solid #2d5a27;
        cursor: pointer;
        box-shadow: 0 4px 20px rgba(45, 90, 39, 0.2);
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.6em 1em;
        border-radius: 10px;
        font-weight: 600;
        letter-spacing: 0.5px;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .bg-success {
        background: linear-gradient(135deg, #2d5a27 0%, #3a6b34 100%) !important;
        box-shadow: 0 4px 15px rgba(45, 90, 39, 0.4);
        color: #ffffff !important;
    }

    .bg-primary {
        background: linear-gradient(135deg, #2c5282 0%, #2a4365 100%) !important;
        box-shadow: 0 4px 15px rgba(44, 82, 130, 0.4);
        color: #ffffff !important;
    }

    .bg-warning {
        background: linear-gradient(135deg, #FFA000 0%, #FF8C00 100%) !important;
        box-shadow: 0 4px 15px rgba(255, 160, 0, 0.4);
        color: #ffffff !important;
    }

    .bg-secondary {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%) !important;
        box-shadow: 0 4px 15px rgba(108, 117, 125, 0.4);
        color: #ffffff !important;
    }

    .bg-info {
        background: linear-gradient(135deg, #0dcaf0 0%, #0aa2c0 100%) !important;
        box-shadow: 0 4px 15px rgba(13, 202, 240, 0.4);
        color: #ffffff !important;
    }

   

    .btn-warning {
        background: linear-gradient(135deg, #FFA000 0%, #FF8C00 100%);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        color: #ffffff;
        transition: all 0.3s ease;
    }

    .btn-warning:hover {
        background: linear-gradient(135deg, #FF8C00 0%, #FFA000 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255, 160, 0, 0.4);
        border-color: rgba(255, 255, 255, 0.3);
        color: #ffffff;
    }

    .btn-danger {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        color: #ffffff;
        transition: all 0.3s ease;
    }

    .btn-danger:hover {
        background: linear-gradient(135deg, #c82333 0%, #dc3545 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(220, 53, 69, 0.4);
        border-color: rgba(255, 255, 255, 0.3);
        color: #ffffff;
    }

    /* Modal styles */
    .modal-content {
        background: rgba(45, 90, 39, 0.15);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(45, 90, 39, 0.4);
        border-radius: 20px;
        color: #ffffff;
    }

    .modal-header {
        background: linear-gradient(135deg, rgba(45, 90, 39, 0.2) 0%, rgba(58, 107, 52, 0.1) 100%);
        border-bottom: 1px solid rgba(45, 90, 39, 0.4);
        color: #ffffff;
    }

    .modal-footer {
        border-top: 1px solid rgba(45, 90, 39, 0.4);
    }

    .btn-secondary {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #ffffff;
    }

    .btn-secondary:hover {
        background: linear-gradient(135deg, #5a6268 0%, #6c757d 100%);
        border-color: rgba(255, 255, 255, 0.3);
        color: #ffffff;
    }

    .text-center {
        text-align: center !important;
    }

    .fa-plus, .fa-edit, .fa-trash {
        color: #ffffff;
    }
</style>

<div class="container mt-5">
    <div class="card shadow-lg mb-5">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-cogs me-2"></i>LISTA DE PROTOTIPOS</span>
            <a href="{{ route('prototipos.create') }}" class="btn btn-primary">
                <i class="fa fa-plus me-2"></i>NUEVOS PROTOTIPOS
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th scope="col">NRO</th>
                            <th scope="col">NOMBRE</th>
                            <th scope="col">SERIAL</th>
                            <th scope="col">ESTADO</th>
                            <th scope="col">PRECIO (BS.)</th>
                            <th scope="col">FECHA DE REGISTRO</th>
                            <th scope="col" class="text-center">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $contador = 1; @endphp
                        @foreach($prototipos as $prototipo)
                            <tr>
                                <td class="fw-bold">{{ $contador++ }}</td>
                                <td>{{ $prototipo->nombre }}</td>
                                <td>{{ $prototipo->serial }}</td>
                                <td>
                                    @switch($prototipo->estado)
                                        @case(1)
                                            <span class="badge bg-success px-3 py-2">PARA ALQUILAR</span>
                                            @break
                                        @case(2)
                                            <span class="badge bg-primary px-3 py-2">PARA VENDER</span>
                                            @break
                                        @case(3)
                                            <span class="badge bg-warning px-3 py-2">MANTENIMIENTO</span>
                                            @break
                                        @case(4)
                                            <span class="badge bg-secondary px-3 py-2">VENDIDO</span>
                                            @break
                                        @case(5)
                                            <span class="badge bg-info px-3 py-2">ALQUILADO</span>
                                            @break
                                        @default
                                            <span class="badge bg-dark px-3 py-2">DESCONOCIDO</span>
                                    @endswitch
                                </td>
                                <td class="fw-semibold">{{ number_format($prototipo->precio, 2) }}</td>
                                <td>{{ $prototipo->fechaRegistro }}</td>
                                <td class="text-center">
                                    <a href="{{ route('prototipos.edit', $prototipo->id) }}" class="btn btn-sm btn-warning me-2" title="Editar">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('prototipos.destroy', $prototipo->id) }}" method="POST" style="display:inline-block;" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $prototipo->id }}" title="Eliminar">
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

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
@endpush

<!-- Modal de confirmación -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteLabel"><i class="fas fa-exclamation-triangle me-2"></i>CONFIRMAR ELIMINACIÓN</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Seguro que deseas eliminar este prototipo?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CANCELAR</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">ELIMINAR</button>
            </div>
        </div>
    </div>
</div>

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

        document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(btn => {
            btn.addEventListener('click', function() {
                if(modalInstance) modalInstance.hide();
            });
        });
    });
</script>
@endsection