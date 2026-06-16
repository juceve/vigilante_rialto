@extends('adminlte::page')

@section('title')
    Perfil de Usuario
@endsection

@section('content_header')
    <h4>Perfil de Usuario</h4>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <!-- Información Personal -->
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user mr-1"></i>
                        Información Personal
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Correo Electrónico</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Estado</label>
                            <p>
                                @if (auth()->user()->status)
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-secondary">Inactivo</span>
                                @endif
                            </p>
                        </div>

                        <div class="form-group">
                            <label>Rol</label>
                            <p>
                                @if(auth()->user()->roles->count() > 0)
                                    <span class="badge badge-primary">{{ auth()->user()->roles->first()->name }}</span>
                                @else
                                    <span class="badge badge-secondary">Sin rol asignado</span>
                                @endif
                            </p>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i>
                            Actualizar Información
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Avatar -->
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-image mr-1"></i>
                        Foto de Perfil
                    </h3>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <img src="{{ auth()->user()->avatar_url }}" 
                             class="img-fluid img-thumbnail" 
                             style="max-width: 200px; max-height: 200px;"
                             alt="Avatar"
                             id="avatar-preview">
                    </div>

                    <form action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="form-group">
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('avatar') is-invalid @enderror" 
                                           id="avatar" name="avatar" accept="image/*" onchange="previewAvatar(this)">
                                    <label class="custom-file-label" for="avatar">Seleccionar imagen</label>
                                </div>
                            </div>
                            @error('avatar')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">
                                Formatos permitidos: JPG, JPEG, PNG, GIF. Tamaño máximo: 2MB
                            </small>
                        </div>

                        <button type="submit" class="btn btn-info">
                            <i class="fas fa-upload mr-1"></i>
                            Subir Avatar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Cambio de Contraseña -->
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-lock mr-1"></i>
                        Cambiar Contraseña
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="current_password">Contraseña Actual</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                       id="current_password" name="current_password" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-eye" id="toggle-current-password" style="cursor: pointer;"></i>
                                    </span>
                                </div>
                                @error('current_password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Nueva Contraseña</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-eye" id="toggle-new-password" style="cursor: pointer;"></i>
                                    </span>
                                </div>
                                @error('password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <small class="form-text text-muted">Mínimo 8 caracteres</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="password_confirmation">Confirmar Nueva Contraseña</label>
                            <div class="input-group">
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-eye" id="toggle-confirm-password" style="cursor: pointer;"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-key mr-1"></i>
                                Cambiar Contraseña
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar-preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
            
            // Actualizar el label del input file
            var fileName = input.files[0].name;
            $(input).next('.custom-file-label').text(fileName);
        }
    }

    // Bootstrap file input label update
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);

    // Toggle password visibility
    function togglePassword(toggleId, inputId) {
        const toggle = document.getElementById(toggleId);
        const input = document.getElementById(inputId);
        
        toggle.addEventListener('click', function() {
            if (input.type === 'password') {
                input.type = 'text';
                toggle.classList.remove('fa-eye');
                toggle.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                toggle.classList.remove('fa-eye-slash');
                toggle.classList.add('fa-eye');
            }
        });
    }

    // Initialize password toggles
    document.addEventListener('DOMContentLoaded', function() {
        togglePassword('toggle-current-password', 'current_password');
        togglePassword('toggle-new-password', 'password');
        togglePassword('toggle-confirm-password', 'password_confirmation');
    });
</script>
@endsection
