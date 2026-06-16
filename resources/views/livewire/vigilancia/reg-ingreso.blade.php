{{-- filepath: c:\laragon\www\vigilantev2\resources\views\livewire\vigilancia\reg-ingreso.blade.php --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/styleVigilancia.css') }}">
@endpush

<div>
    @section('title')
    INGRESOS
    @endsection

    <!-- Header Corporativo -->
    <div class="patrol-header">
        <div class="container">
            <div class="header-navigation">
                <a href="{{ route('vigilancia.panelvisitas',$designacion->id) }}" class="back-button">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="header-title">
                    <h1 class="title-text">REGISTRO DE INGRESOS</h1>
                    <p class="subtitle-text">Control de acceso de visitantes</p>
                </div>
                <div class="header-status">
                    <div class="status-indicator"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido Principal -->
    <div class="patrol-content">
        <!-- Información de Asignación -->
        <div class="assignment-card">
            <div class="assignment-header">
                <i class="fas fa-building"></i>
                <span>Cliente Asignado</span>
            </div>
            <div class="assignment-body">
                <div class="assignment-item">
                    <div class="assignment-label">Ubicación</div>
                    <div class="assignment-value">{{ $designacion->turno->cliente->nombre }}</div>
                </div>
            </div>
        </div>

        <!-- Formulario de Registro -->
        <div class="form-card">
            <div class="form-header">
                <i class="fas fa-user-plus"></i>
                <span>DATOS DEL VISITANTE</span>
            </div>
            <div class="form-body">
                <div class="row">
                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">
                            <i class="fas fa-id-card"></i>
                            Doc. Identidad:
                        </label>
                        <div class="search-group">
                            <input type="search" class="form-input" wire:model.defer='docidentidad'
                                placeholder="Ingrese documento de identidad"
                                onkeyup="this.value=this.value.toUpperCase()">
                            <button class="search-btn" type="button" wire:click='buscar'>
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        @error('docidentidad')
                        <small class="error-text">El campo Doc. Identidad es requerido</small>
                        @enderror
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">
                            <i class="fas fa-user"></i>
                            Nombre Visitante:
                        </label>
                        <input type="text" class="form-input" wire:model='nombrevisitante'
                            placeholder="Nombre completo del visitante"
                            onkeyup="this.value=this.value.toUpperCase()">
                        @error('nombrevisitante')
                        <small class="error-text">El campo Nombre Visitante es requerido</small>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">
                            <i class="fas fa-home"></i>
                            A quien visita:
                        </label>
                        <input type="search" class="form-input" wire:model='residente'
                            placeholder="Nombre del residente"
                            onkeyup="this.value=this.value.toUpperCase()">
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">
                            <i class="fas fa-map-marker-alt"></i>
                            Nro. vivienda:
                        </label>
                        <input type="search" class="form-input" wire:model='nrovivienda'
                            placeholder="Número de vivienda"
                            onkeyup="this.value=this.value.toUpperCase()">
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">
                            <i class="fas fa-clipboard-list"></i>
                            Motivo visita:
                        </label>
                        {!! Form::select('motivo_id', $motivos, null,
                        ['class'=>'form-select-custom','wire:model'=>'motivoid','placeholder'=>'Seleccione un motivo']) !!}
                        @error('motivoid')
                        <small class="error-text">El campo Motivo Visita es requerido</small>
                        @enderror
                    </div>

                    @if ($motivo->nombre=="Otros")
                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label special-label">
                            <i class="fas fa-edit"></i>
                            Otro motivo:
                        </label>
                        <input type="text" class="form-input" wire:model='otros'
                            placeholder="Especifique el motivo"
                            onkeyup="this.value=this.value.toUpperCase()">
                    </div>
                    @endif
                    <div class="col-12 mb-3">
                        <label class="form-label">
                            <i class="fas fa-comment-alt"></i>
                            Observaciones:
                        </label>
                        <input type="text" class="form-input" wire:model='observaciones'
                            placeholder="Observaciones adicionales (opcional)">
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">
                            <i class="fas fa-camera"></i>
                            Capturas:
                        </label>
                        <div class="capture-section" wire:ignore>
                            <form id="uploadForm">
                                <div class="row mb-3 input-row" id="inputRow0">
                                    <div class="col">
                                        <input type="file" class="file-input" id="fileInput0" name="fileInput0"
                                            onchange="CARGAFOTO('fileInput0')" accept="image/*,audio/*" capture="camera">
                                    </div>
                                    <div class="col-auto" id="thumbnailContainer0"></div>
                                    <div class="col-auto d-none" id="deleteButtonContainer0">
                                        <button type="button" class="delete-btn"
                                            onclick="deleteInput('inputRow0');remArray(0)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón de Registro -->
        <div class="action-section">
            <button class="register-btn" onclick='registrar()' wire:loading.remove wire:loading.attr="disabled">
                <i class="fas fa-save"></i>
                <span>REGISTRAR VISITA</span>
            </button>

            <button class="register-btn loading" wire:loading wire:target='registrar' wire:loading.attr="disabled">
                <div class="spinner-border spinner-border-sm" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <span>Espere...</span>
            </button>
        </div>
        <br>
    </div> <!-- Cierre patrol-content -->
</div>

@section('css')
<style>
    /* Variables CSS - Paleta Empresarial de Seguridad */
    :root {
        --primary-color: #1e3a8a;          /* Azul naval profundo */
        --primary-dark: #1e293b;           /* Azul oscuro casi negro */
        --primary-light: #3b82f6;          /* Azul corporativo */
        --secondary-color: #334155;        /* Gris azulado */
        --secondary-dark: #1e293b;         /* Gris oscuro */
        --accent-color: #d97706;           /* Dorado corporativo */
        --accent-light: #f59e0b;          /* Dorado claro */
        --success-color: #059669;          /* Verde profesional */
        --warning-color: #d97706;          /* Naranja dorado */
        --error-color: #dc2626;           /* Rojo corporativo */
        --info-color: #0891b2;            /* Azul información */
        --surface-color: #ffffff;
        --background-color: #f8fafc;      /* Gris muy claro */
        --on-surface: #1e293b;            /* Texto principal */
        --text-secondary: #64748b;        /* Texto secundario */
        --shadow-light: 0 2px 8px rgba(30, 41, 59, 0.1);
        --shadow-medium: 0 4px 16px rgba(30, 41, 59, 0.15);
        --shadow-heavy: 0 8px 24px rgba(30, 41, 59, 0.2);
        --border-radius: 16px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Forzar modo claro permanente */
    * {
        color-scheme: light !important;
    }

    html,
    body {
        background-color: #f8fafc !important;
        color: #1e293b !important;
    }

    /* Estilos Generales */
    body {
        background-color: var(--background-color);
        font-family: 'Montserrat', sans-serif;
    }

    /* Header Corporativo */
    .patrol-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        padding: 1.5rem 0;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-medium);
    }

    .header-navigation {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: var(--border-radius);
        padding: 1rem 1.5rem;
        box-shadow: var(--shadow-light);
    }

    .back-button {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, var(--accent-color), var(--accent-light));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        font-size: 1.2rem;
        transition: var(--transition);
        box-shadow: var(--shadow-light);
    }

    .back-button:hover {
        transform: scale(1.05);
        box-shadow: var(--shadow-medium);
        color: white;
    }

    .header-title {
        text-align: center;
        flex: 1;
        margin: 0 1rem;
    }

    .title-text {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--on-surface);
        margin: 0;
        letter-spacing: 0.5px;
    }

    .subtitle-text {
        font-size: 0.85rem;
        color: var(--text-secondary);
        margin: 0.2rem 0 0 0;
        font-weight: 500;
    }

    .header-status {
        width: 50px;
        display: flex;
        justify-content: center;
    }

    .status-indicator {
        width: 12px;
        height: 12px;
        background: var(--success-color);
        border-radius: 50%;
        animation: pulse-patrol 2s infinite;
    }

    /* Contenido Principal */
    .patrol-content {
        padding: 0 1rem;
        max-width: 1000px;
        margin: 0 auto;
    }

    /* Card de Asignación */
    .assignment-card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-light);
        overflow: hidden;
        margin-bottom: 2rem;
        border: 2px solid var(--primary-color);
    }

    .assignment-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        font-weight: 600;
        font-size: 1rem;
    }

    .assignment-header i {
        margin-right: 0.75rem;
        font-size: 1.2rem;
    }

    .assignment-body {
        padding: 1.5rem;
    }

    .assignment-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
    }

    .assignment-label {
        font-weight: 600;
        color: var(--text-secondary);
        font-size: 0.9rem;
    }

    .assignment-value {
        font-weight: 700;
        color: var(--on-surface);
        font-size: 1rem;
        text-align: right;
    }

    /* Card del Formulario */
    .form-card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-light);
        overflow: hidden;
        margin-bottom: 2rem;
        border: 2px solid var(--success-color);
    }

    .form-header {
        background: linear-gradient(135deg, var(--success-color), #10b981);
        color: white;
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        font-weight: 600;
        font-size: 1rem;
    }

    .form-header i {
        margin-right: 0.75rem;
        font-size: 1.2rem;
    }

    .form-body {
        padding: 2rem;
    }

    /* Elementos del Formulario */
    .form-label {
        display: flex;
        align-items: center;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--on-surface);
        margin-bottom: 0.5rem;
    }

    .form-label i {
        margin-right: 0.5rem;
        color: var(--primary-color);
        width: 16px;
    }

    .special-label {
        color: var(--primary-color);
    }

    .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: calc(var(--border-radius) / 2);
        font-size: 0.9rem;
        transition: var(--transition);
        background: white;
    }

    .form-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    .form-select-custom {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: calc(var(--border-radius) / 2);
        font-size: 0.9rem;
        transition: var(--transition);
        background: white;
        cursor: pointer;
    }

    .form-select-custom:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    .search-group {
        display: flex;
        gap: 0.5rem;
        align-items: stretch;
    }

    .search-group .form-input {
        flex: 1;
    }

    .search-btn {
        padding: 0.75rem 1rem;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        border: none;
        border-radius: calc(var(--border-radius) / 2);
        cursor: pointer;
        transition: var(--transition);
        box-shadow: var(--shadow-light);
        min-width: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .search-btn:hover {
        background: linear-gradient(135deg, var(--primary-dark), #0f172a);
        transform: translateY(-2px);
        box-shadow: var(--shadow-medium);
    }

    .error-text {
        color: var(--error-color);
        font-size: 0.8rem;
        font-weight: 500;
        margin-top: 0.25rem;
        display: block;
    }

    /* Sección de Capturas */
    .capture-section {
        background: var(--background-color);
        border-radius: calc(var(--border-radius) / 2);
        padding: 1rem;
        border: 1px solid #e2e8f0;
    }

    .file-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px dashed var(--primary-color);
        border-radius: calc(var(--border-radius) / 2);
        font-size: 0.9rem;
        transition: var(--transition);
        background: white;
        cursor: pointer;
    }

    .file-input:hover {
        border-color: var(--primary-dark);
        background: var(--background-color);
    }

    .delete-btn {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, var(--error-color), #ef4444);
        color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .delete-btn:hover {
        background: linear-gradient(135deg, #b91c1c, var(--error-color));
        transform: scale(1.1);
    }

    /* Sección de Acción */
    .action-section {
        display: flex;
        justify-content: center;
        margin: 2rem 0;
    }

    .register-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding: 1rem 3rem;
        background: linear-gradient(135deg, var(--success-color), #10b981);
        color: white;
        border: none;
        border-radius: calc(var(--border-radius) / 2);
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: var(--transition);
        box-shadow: var(--shadow-light);
        letter-spacing: 0.5px;
        width: 100%;
        max-width: 300px;
    }

    .register-btn:hover:not(.loading) {
        background: linear-gradient(135deg, #047857, var(--success-color));
        transform: translateY(-2px);
        box-shadow: var(--shadow-medium);
    }

    .register-btn.loading {
        background: var(--secondary-color);
        cursor: not-allowed;
    }

    /* Thumbnails */
    .img-thumbnail {
        border-radius: calc(var(--border-radius) / 3);
        border: 2px solid var(--primary-color);
        box-shadow: var(--shadow-light);
    }

    /* Animaciones */
    @keyframes pulse-patrol {
        0% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.2);
            opacity: 0.7;
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .header-navigation {
            padding: 0.8rem 1rem;
        }

        .title-text {
            font-size: 1.1rem;
        }

        .subtitle-text {
            font-size: 0.75rem;
        }

        .back-button {
            width: 45px;
            height: 45px;
            font-size: 1.1rem;
        }

        .patrol-content {
            padding: 0 0.5rem;
        }

        .assignment-body {
            padding: 1rem;
        }

        .form-body {
            padding: 1.5rem 1rem;
        }

        .search-group {
            flex-direction: column;
            gap: 1rem;
        }

        .register-btn {
            padding: 0.875rem 2rem;
            font-size: 0.9rem;
        }
    }

    @media (max-width: 480px) {
        .header-navigation {
            padding: 0.7rem 0.8rem;
        }

        .title-text {
            font-size: 1rem;
        }

        .subtitle-text {
            font-size: 0.7rem;
        }

        .back-button {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }

        .header-title {
            margin: 0 0.5rem;
        }

        .assignment-header,
        .form-header {
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
        }

        .assignment-body,
        .form-body {
            padding: 1rem 0.75rem;
        }

        .form-input,
        .form-select-custom {
            padding: 0.625rem 0.75rem;
            font-size: 0.85rem;
        }

        .search-btn {
            padding: 0.625rem 0.75rem;
            min-width: 45px;
        }

        .register-btn {
            padding: 0.75rem 1.5rem;
            font-size: 0.85rem;
            gap: 0.5rem;
        }
    }
</style>
@endsection

@section('js')
{{-- <script src="{{asset('vendor/jquery/inputsfiles.js')}}"></script> --}}
<script>
    function registrar(){
        Swal.fire({
            title: "REGISTRAR VISITA",
            text: "Está seguro de realizar esta operación?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#146c43",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, registrar",
            cancelButtonText: "No, cancelar",
            }).then((result) => {
            if (result.isConfirmed) {
                @this.registrar();
            }
            });
        }
</script>
<script>
    let inputCount = 1;
    function remArray(id){
        Livewire.emit('deleteInput',id);
    }

    function CARGAFOTO(inputId) {
        var srcEncoded;
        @this.set('filename',"");
        const inputElement = document.getElementById(inputId);
        const file = inputElement.files[0];
        if (file) {
            const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                document.getElementById(inputId).value="";
                Swal.fire({
                    title: "Error",
                    text: "Solo se permiten imagenes.",
                    icon: "error"
                });
                event.preventDefault();
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                const thumbnailContainerId = `thumbnailContainer${inputId.replace('fileInput', '')}`;
                createThumbnail(e.target.result, thumbnailContainerId);
                showDeleteButton(inputId);
                createNewFileInput();

                const imgElement = document.createElement("img");
                imgElement.src = event.target.result;

                imgElement.onload = function (e) {
                    const canvas = document.createElement("canvas");
                    const MAX_WIDTH = 400;

                    const scaleSize = MAX_WIDTH / e.target.width;
                    canvas.width = MAX_WIDTH;
                    canvas.height = e.target.height * scaleSize;

                    const ctx = canvas.getContext("2d");

                    ctx.drawImage(e.target, 0, 0, canvas.width, canvas.height);

                    srcEncoded = ctx.canvas.toDataURL(e.target, "image/jpeg");

                    @this.cargaImagenBase64(srcEncoded);
                };
            }
            reader.readAsDataURL(file);
        }
        inputElement.disabled=true;
    }

    function createThumbnail(src, containerId) {
        const thumbnailContainer = document.getElementById(containerId);
        thumbnailContainer.innerHTML = '';
        const img = document.createElement('img');
        img.src = src;
        img.className = 'img-thumbnail';
        img.style.maxWidth = '100px';
        thumbnailContainer.appendChild(img);
    }

    function showDeleteButton(inputId) {
        const deleteButtonContainerId = `deleteButtonContainer${inputId.replace('fileInput', '')}`;
        const deleteButtonContainer = document.getElementById(deleteButtonContainerId);
        deleteButtonContainer.classList.remove('d-none');
    }

    function createNewFileInput() {
        const form = document.getElementById('uploadForm');
        const divRow = document.createElement('div');
        divRow.className = 'row mb-3 input-row';
        divRow.id = `inputRow${inputCount}`;

        const divInput = document.createElement('div');
        divInput.className = 'col';

        const label = document.createElement('label');
        label.className = 'form-label';
        label.textContent = 'Upload Image';

        const input = document.createElement('input');
        input.type = 'file';
        input.className = 'form-control';
        input.id = `fileInput${inputCount}`;
        input.name = `fileInput${inputCount}`;
        input.accept = 'image/*,audio/*';
        input.setAttribute('capture', `camera`);
        input.setAttribute('onchange', `CARGAFOTO('${input.id}')`);

        const divThumbnail = document.createElement('div');
        divThumbnail.className = 'col-auto';
        divThumbnail.id = `thumbnailContainer${inputCount}`;

        const divButton = document.createElement('div');
        divButton.className = 'col-auto d-none';
        divButton.id = `deleteButtonContainer${inputCount}`;
        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'btn btn-danger';
        button.textContent = 'x';
        button.setAttribute('onclick', `deleteInput('${divRow.id}');remArray('${inputCount}')`);
        // button.setAttribute('wire:click', `deleteInput('${inputCount}')`);

        divInput.appendChild(label);
        divInput.appendChild(input);
        divButton.appendChild(button);
        divRow.appendChild(divInput);
        divRow.appendChild(divThumbnail);
        divRow.appendChild(divButton);

        form.appendChild(divRow);
        inputCount++;
    }

    function deleteInput(rowId) {
        const row = document.getElementById(rowId);
        row.remove();
        // Ensure at least one empty input file remains
        const inputs = document.querySelectorAll('input[type="file"]');
        if (inputs.length === 0) {
            createNewFileInput();
        }
    }
</script>

@endsection
