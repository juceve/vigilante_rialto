<div class="box box-info padding-1">
    <div class="box-body">


        <div class="form-group mb-3">
            <label for="name">Nombre:</label>
            <input type="text" name="name" required class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name') }}" placeholder="Nombre completo">
            @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="email">Correo electrónico:</label>
            <input type="email" name="email" required class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}" placeholder="Correo electronico">

            @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror

        </div>

        <div class="form-group">
            <label for="password">Contraseña:</label>
            <div class="input-group">
                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                    id="password" placeholder="Contraseña">
                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="fas fa-eye" onclick="togglePasswordVisibility('password')"></i>
                    </span>
                </div>
            </div>
            @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirmar Contraseña:</label>
            <div class="input-group">
                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                    placeholder="Confirmar Contraseña" name="password_confirmation" id="password_confirmation" required>
                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="fas fa-eye" onclick="togglePasswordVisibility('password_confirmation')"></i>
                    </span>
                </div>
            </div>
            @error('password_confirmation')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>
    <div class="col-12 col-md-3 d-grid">
        <button type="submit" class="btn btn-primary">Guardar <i class="fas fa-save"></i></button>
    </div>
</div>