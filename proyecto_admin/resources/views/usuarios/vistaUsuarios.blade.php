@extends('menu')

@section('contenido')

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-success text-white text-center py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="mb-0">Lista de Agricultores</h2>
                            <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
                                <i class="fa fa-user-plus"></i> Nuevo Usuario
                            </a>
                        </div>
                    </div>
                    <div class="card-body bg-light">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-success">
                                    <tr>
                                        <th scope="col">Nro</th>
                                        <th scope="col">Nombres</th>
                                        <th scope="col">Apellidos</th>
                                        <th scope="col">Correo</th>
                                        <th scope="col">Rol</th>
                                        <th scope="col" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $contador = 1; @endphp
                                    @foreach($usuarios as $usuario)
                                        <tr>
                                            <td>{{ $contador++ }}</td>
                                            <td>{{ $usuario->nombres }}</td>
                                            <td>{{ $usuario->apellidos }}</td>
                                            <td>{{ $usuario->correo }}</td>
                                            <td>
                                                <span class="badge bg-success bg-opacity-75 px-3 py-2">{{ $usuario->rol }}</span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-sm btn-warning me-1" title="Editar">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" style="display:inline-block;" class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $usuario->id }}" title="Eliminar">
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
            ¿Seguro que deseas eliminar este usuario?
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
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    formToSubmit = this.closest('form');
                    const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
                    modal.show();
                });
            });
            document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                if(formToSubmit) formToSubmit.submit();
            });
        });
    </script>
@endsection