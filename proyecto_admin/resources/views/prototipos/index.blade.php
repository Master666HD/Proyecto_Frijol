{{-- filepath: resources/views/prototipos/index.blade.php --}}
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
    .card h3 {
        font-size: 2rem;
        margin-top: 10px;
    }
</style>
<div class="container mt-4">
    <div class="card shadow-sm mb-4" style="background-color: #1b1f24; color: #e0e0e0;">
        <div class="card-header d-flex justify-content-between align-items-center fs-5">
            Lista de Prototipos
            <a href="{{ route('prototipos.create') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> Nuevos Prototipos
            </a>
        </div>
        <div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-success">
                        <tr>
                            <th scope="col">Nro</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Serial</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Precio (Bs.)</th>
                            <th scope="col">Fecha de Registro</th>
                            <th scope="col" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $contador = 1; @endphp
                        @foreach($prototipos as $prototipo)
                            <tr>
                                <td>{{ $contador++ }}</td>
                                <td>{{ $prototipo->nombre }}</td>
                                <td>{{ $prototipo->serial }}</td>
                                <td>
                                    @switch($prototipo->estado)
                                        @case(1)
                                            <span class="badge bg-success bg-opacity-75 px-3 py-2">Para alquilar</span>
                                            @break
                                        @case(2)
                                            <span class="badge bg-primary bg-opacity-75 px-3 py-2">Para vender</span>
                                            @break
                                        @case(3)
                                            <span class="badge bg-warning bg-opacity-75 px-3 py-2">Mantenimiento</span>
                                            @break
                                        @case(4)
                                            <span class="badge bg-secondary bg-opacity-75 px-3 py-2">Vendido</span>
                                            @break
                                        @case(5)
                                            <span class="badge bg-info bg-opacity-75 px-3 py-2">Alquilado</span>
                                            @break
                                        @default
                                            <span class="badge bg-dark bg-opacity-75 px-3 py-2">Desconocido</span>
                                    @endswitch
                                </td>
                                <td>{{ number_format($prototipo->precio, 2) }}</td>
                                <td>{{ $prototipo->fechaRegistro }}</td>
                                <td class="text-center">
                                    <a href="{{ route('prototipos.edit', $prototipo->id) }}" class="btn btn-sm btn-warning me-1" title="Editar">
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
                        @push('styles')
                        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
                        @endpush
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Modal de confirmación -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="confirmDeleteLabel">Confirmar eliminación</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            ¿Seguro que deseas eliminar este prototipo?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Eliminar</button>
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
</div>
@endsection