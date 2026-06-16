{{-- filepath: c:\laragon\www\vigilantev2\resources\views\livewire\vigilancia\vtareas.blade.php --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/styleVigilancia.css') }}">
@endpush

<div>
    @section('title')
    TAREAS
    @endsection

    <!-- Header Corporativo -->
    <div class="tasks-header">
        <div class="container">
            <div class="header-navigation">
                <a href="{{ route('home') }}" class="back-button">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="header-title">
                    <h1 class="title-text">GESTIÓN DE TAREAS</h1>
                    <p class="subtitle-text">Control y Seguimiento</p>
                </div>
                <div class="header-status">
                    <div class="status-indicator"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido Principal -->
    <div class="tasks-content">
        <!-- Información del Cliente -->
        <div class="client-info-card">
            <div class="client-info-header">
                <i class="fas fa-building"></i>
                <span>Cliente Asignado</span>
            </div>
            <div class="client-info-body">
                <h3 class="client-name">{{ $designacion->turno->cliente->nombre }}</h3>
            </div>
        </div>

        <!-- Sección de Tareas Pendientes -->
        <div class="tasks-section">
            <div class="tasks-header-card">
                <div class="tasks-header-content">
                    <i class="fas fa-tasks"></i>
                    <div class="tasks-header-info">
                        <h5 class="tasks-title">Tareas Pendientes</h5>
                        <p class="tasks-subtitle">Lista de actividades por completar</p>
                    </div>
                    <div class="tasks-counter">
                        <span class="counter-badge">{{ count($tareas) }}</span>
                    </div>
                </div>
            </div>

            <!-- Lista de Tareas -->
            <div class="tasks-list">
                @forelse ($tareas as $item)
                <div class="task-card">
                    <div class="task-content">
                        <div class="task-date">
                            <i class="fas fa-calendar-alt"></i>
                            <span>{{ $item->fecha }}</span>
                        </div>
                        <div class="task-description">
                            <p>{{ $item->contenido }}</p>
                        </div>
                    </div>
                    <div class="task-actions">
                        <button class="task-action-btn" data-bs-toggle="modal" data-bs-target="#modalInfo"
                                wire:click='cargarTarea({{ $item->id }})' title="Procesar Tarea">
                            <i class="fas fa-check-double"></i>
                            <span>Procesar</span>
                        </button>
                    </div>
                </div>
                @empty
                <div class="empty-tasks-card">
                    <div class="empty-tasks-icon">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <div class="empty-tasks-content">
                        <h6 class="empty-tasks-title">No hay tareas pendientes</h6>
                        <p class="empty-tasks-message">Todas las tareas han sido completadas.</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    <!-- Modal Corporativo -->
    <div class="modal fade" id="modalInfo" tabindex="-1" aria-labelledby="modalInfoLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content task-modal">
                <div class="modal-header task-modal-header">
                    <div class="modal-title-section">
                        <i class="fas fa-clipboard-list"></i>
                        <div class="modal-title-content">
                            <h5 class="modal-title" id="modalInfoLabel">Procesar Tarea</h5>
                            <p class="modal-subtitle">Detalles y finalización</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body task-modal-body">
                    <!-- Información de la Tarea -->
                    <div class="task-info-section">
                        @if ($tarea)
                        <div class="task-info-grid">
                            <div class="task-info-item">
                                <div class="task-info-label">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>Fecha</span>
                                </div>
                                <div class="task-info-value">{{ $tarea->fecha }}</div>
                            </div>
                            <div class="task-info-item">
                                <div class="task-info-label">
                                    <i class="fas fa-building"></i>
                                    <span>Cliente</span>
                                </div>
                                <div class="task-info-value">{{ $tarea->cliente->nombre }}</div>
                            </div>
                            <div class="task-info-item">
                                <div class="task-info-label">
                                    <i class="fas fa-user-shield"></i>
                                    <span>Operador</span>
                                </div>
                                <div class="task-info-value">{{ $tarea->empleado->nombres }}</div>
                            </div>
                            <div class="task-info-item task-description-item">
                                <div class="task-info-label">
                                    <i class="fas fa-file-alt"></i>
                                    <span>Descripción</span>
                                </div>
                                <div class="task-info-description">{{ $tarea->contenido }}</div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Sección de Evidencia -->
                    <div class="evidence-section">
                        <div class="evidence-header">
                            <i class="fas fa-camera"></i>
                            <span>Evidencia Fotográfica</span>
                        </div>
                        <div class="evidence-body" wire:ignore>
                            <form id="uploadForm" class="upload-form">
                                <div class="upload-item" id="inputRow0">
                                    <div class="upload-input-container">
                                        <input type="file" class="upload-input" id="fileInput0" name="fileInput0"
                                            onchange="CARGAFOTO('fileInput0')" accept="image/*,audio/*" capture="camera">
                                        <div class="upload-label">
                                            <i class="fas fa-camera"></i>
                                            <span>Capturar evidencia</span>
                                        </div>
                                    </div>
                                    <div class="thumbnail-container" id="thumbnailContainer0"></div>
                                    <div class="delete-container d-none" id="deleteButtonContainer0">
                                        <button type="button" class="delete-button"
                                            onclick="deleteInput('inputRow0');remArray(0)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer task-modal-footer">
                    <button type="button" class="cancel-button" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                        <span>Cancelar</span>
                    </button>
                    <button type="button" class="complete-button" wire:click='procesar' wire:loading.attr="disabled">
                        <i class="fas fa-check-double"></i>
                        <span>Finalizar Tarea</span>
                        <div class="loading-indicator" wire:loading wire:target="procesar">
                            <div class="spinner-border spinner-border-sm" role="status">
                                <span class="visually-hidden">Procesando...</span>
                            </div>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
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
    .tasks-header {
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
        animation: pulse-tasks 2s infinite;
    }

    /* Contenido Principal */
    .tasks-content {
        padding: 0 1rem;
        max-width: 900px;
        margin: 0 auto;
    }

    /* Información del Cliente */
    .client-info-card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-light);
        margin-bottom: 2rem;
        overflow: hidden;
        border: 2px solid var(--primary-color);
    }

    .client-info-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
        font-weight: 600;
    }

    .client-info-header i {
        font-size: 1.2rem;
    }

    .client-info-body {
        padding: 1.5rem;
        text-align: center;
    }

    .client-name {
        margin: 0;
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--on-surface);
        letter-spacing: 0.5px;
    }

    /* Sección de Tareas */
    .tasks-section {
        margin-bottom: 2rem;
    }

    .tasks-header-card {
        background: linear-gradient(135deg, var(--accent-color), var(--accent-light));
        border-radius: var(--border-radius);
        padding: 1.2rem 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-light);
    }

    .tasks-header-content {
        display: flex;
        align-items: center;
        gap: 1rem;
        color: white;
    }

    .tasks-header-content i {
        font-size: 1.4rem;
    }

    .tasks-header-info {
        flex: 1;
    }

    .tasks-title {
        margin: 0;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .tasks-subtitle {
        margin: 0.3rem 0 0 0;
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .tasks-counter {
        display: flex;
        align-items: center;
    }

    .counter-badge {
        background: rgba(255, 255, 255, 0.2);
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-weight: 700;
        font-size: 1rem;
    }

    /* Lista de Tareas */
    .tasks-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .task-card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-light);
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        transition: var(--transition);
        border-left: 4px solid var(--accent-color);
    }

    .task-card:hover {
        box-shadow: var(--shadow-medium);
        transform: translateY(-2px);
    }

    .task-content {
        flex: 1;
    }

    .task-date {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-secondary);
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 0.8rem;
    }

    .task-date i {
        color: var(--accent-color);
    }

    .task-description {
        margin: 0;
    }

    .task-description p {
        margin: 0;
        color: var(--on-surface);
        font-weight: 500;
        line-height: 1.5;
    }

    .task-actions {
        display: flex;
        align-items: center;
    }

    .task-action-btn {
        background: linear-gradient(135deg, var(--success-color), #047857);
        color: white;
        border: none;
        padding: 0.8rem 1.2rem;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
    }

    .task-action-btn:hover {
        background: linear-gradient(135deg, #047857, #065f46);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(5, 150, 105, 0.4);
    }

    .task-action-btn i {
        font-size: 1rem;
    }

    /* Estado Vacío */
    .empty-tasks-card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-light);
        padding: 3rem 2rem;
        text-align: center;
        border: 2px dashed var(--text-secondary);
    }

    .empty-tasks-icon {
        width: 80px;
        height: 80px;
        background: var(--success-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        color: white;
        font-size: 2rem;
    }

    .empty-tasks-title {
        margin: 0 0 0.5rem 0;
        font-weight: 700;
        color: var(--on-surface);
        font-size: 1.1rem;
    }

    .empty-tasks-message {
        margin: 0;
        color: var(--text-secondary);
        font-weight: 500;
    }

    /* Modal Corporativo */
    .task-modal .modal-content {
        border: none;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-heavy);
        overflow: hidden;
    }

    .task-modal-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        padding: 1.5rem;
        border-bottom: none;
    }

    .modal-title-section {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .modal-title-section i {
        font-size: 1.5rem;
    }

    .modal-title-content h5 {
        margin: 0;
        font-weight: 700;
        font-size: 1.2rem;
    }

    .modal-subtitle {
        margin: 0.3rem 0 0 0;
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .task-modal-body {
        padding: 2rem;
        background: var(--background-color);
    }

    /* Información de la Tarea */
    .task-info-section {
        background: white;
        border-radius: var(--border-radius);
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-light);
    }

    .task-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .task-info-item {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .task-description-item {
        grid-column: 1 / -1;
    }

    .task-info-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-secondary);
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .task-info-label i {
        color: var(--accent-color);
    }

    .task-info-value {
        color: var(--on-surface);
        font-weight: 600;
        font-size: 1rem;
    }

    .task-info-description {
        color: var(--on-surface);
        font-weight: 500;
        line-height: 1.5;
        padding: 1rem;
        background: var(--background-color);
        border-radius: 8px;
        border-left: 4px solid var(--accent-color);
    }

    /* Sección de Evidencia */
    .evidence-section {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-light);
        overflow: hidden;
    }

    .evidence-header {
        background: linear-gradient(135deg, var(--info-color), #0e7490);
        color: white;
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
        font-weight: 600;
    }

    .evidence-body {
        padding: 1.5rem;
    }

    .upload-form {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .upload-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border: 2px dashed var(--info-color);
        border-radius: 12px;
        background: rgba(8, 145, 178, 0.05);
        transition: var(--transition);
    }

    .upload-item:hover {
        border-color: #0e7490;
        background: rgba(8, 145, 178, 0.1);
    }

    .upload-input-container {
        position: relative;
        flex: 1;
    }

    .upload-input {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }

    .upload-label {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        color: var(--info-color);
        font-weight: 600;
        padding: 0.8rem 1rem;
        border-radius: 8px;
        background: white;
        border: 2px solid var(--info-color);
        cursor: pointer;
        transition: var(--transition);
    }

    .upload-label:hover {
        background: var(--info-color);
        color: white;
    }

    .thumbnail-container img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: var(--shadow-light);
    }

    .delete-button {
        background: var(--error-color);
        color: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .delete-button:hover {
        background: #b91c1c;
        transform: scale(1.1);
    }

    /* Footer del Modal */
    .task-modal-footer {
        background: white;
        padding: 1.5rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
    }

    .cancel-button {
        background: var(--text-secondary);
        color: white;
        border: none;
        padding: 0.8rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .cancel-button:hover {
        background: var(--secondary-color);
    }

    .complete-button {
        background: linear-gradient(135deg, var(--success-color), #047857);
        color: white;
        border: none;
        padding: 0.8rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        position: relative;
    }

    .complete-button:hover {
        background: linear-gradient(135deg, #047857, #065f46);
        transform: translateY(-1px);
    }

    .complete-button:disabled {
        opacity: 0.6;
        transform: none;
    }

    .loading-indicator {
        margin-left: 0.5rem;
    }

    /* Animaciones */
    @keyframes pulse-tasks {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.7; transform: scale(1.1); }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .tasks-content {
            padding: 0 0.5rem;
        }

        .header-navigation {
            padding: 0.8rem 1rem;
        }

        .title-text {
            font-size: 1.1rem;
        }

        .task-card {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .task-actions {
            width: 100%;
            justify-content: center;
        }

        .task-action-btn {
            width: 100%;
            justify-content: center;
        }

        .task-info-grid {
            grid-template-columns: 1fr;
        }

        .upload-item {
            flex-direction: column;
            align-items: stretch;
        }

        .task-modal-footer {
            flex-direction: column;
        }

        .cancel-button, .complete-button {
            width: 100%;
            justify-content: center;
        }
    }

    /* Modo Oscuro */
    @media (prefers-color-scheme: dark) {
        :root {
            --surface-color: #1e293b;
            --background-color: #0f172a;
            --on-surface: #e2e8f0;
            --text-secondary: #94a3b8;
        }

        body {
            background-color: var(--background-color);
            color: var(--on-surface);
        }

        .client-info-card, .task-card, .empty-tasks-card,
        .task-info-section, .evidence-section {
            background: var(--surface-color);
            border-color: var(--secondary-color);
        }

        .header-navigation {
            background: rgba(30, 41, 59, 0.95);
        }

        .upload-label {
            background: var(--surface-color);
            border-color: var(--info-color);
        }

        .task-modal .modal-content {
            background: var(--surface-color);
        }

        .task-modal-body {
            background: var(--background-color);
        }

        .task-modal-footer {
            background: var(--surface-color);
        }
    }
</style>
@endsection

@section('js')
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
                    text: "Solo se permiten imágenes.",
                    icon: "error",
                    confirmButtonColor: "var(--primary-color)"
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
        inputElement.disabled = true;
    }

    function createThumbnail(src, containerId) {
        const thumbnailContainer = document.getElementById(containerId);
        thumbnailContainer.innerHTML = '';
        const img = document.createElement('img');
        img.src = src;
        img.className = 'thumbnail-image';
        thumbnailContainer.appendChild(img);
    }

    function showDeleteButton(inputId) {
        const deleteButtonContainerId = `deleteButtonContainer${inputId.replace('fileInput', '')}`;
        const deleteButtonContainer = document.getElementById(deleteButtonContainerId);
        deleteButtonContainer.classList.remove('d-none');
    }

    function createNewFileInput() {
        const form = document.getElementById('uploadForm');
        const uploadItem = document.createElement('div');
        uploadItem.className = 'upload-item';
        uploadItem.id = `inputRow${inputCount}`;

        const inputContainer = document.createElement('div');
        inputContainer.className = 'upload-input-container';

        const input = document.createElement('input');
        input.type = 'file';
        input.className = 'upload-input';
        input.id = `fileInput${inputCount}`;
        input.name = `fileInput${inputCount}`;
        input.accept = 'image/*,audio/*';
        input.setAttribute('capture', 'camera');
        input.setAttribute('onchange', `CARGAFOTO('${input.id}')`);

        const label = document.createElement('div');
        label.className = 'upload-label';
        label.innerHTML = '<i class="fas fa-camera"></i><span>Capturar evidencia adicional</span>';

        const thumbnailContainer = document.createElement('div');
        thumbnailContainer.className = 'thumbnail-container';
        thumbnailContainer.id = `thumbnailContainer${inputCount}`;

        const deleteContainer = document.createElement('div');
        deleteContainer.className = 'delete-container d-none';
        deleteContainer.id = `deleteButtonContainer${inputCount}`;

        const deleteButton = document.createElement('button');
        deleteButton.type = 'button';
        deleteButton.className = 'delete-button';
        deleteButton.innerHTML = '<i class="fas fa-trash"></i>';
        deleteButton.setAttribute('onclick', `deleteInput('${uploadItem.id}');remArray('${inputCount}')`);

        inputContainer.appendChild(input);
        inputContainer.appendChild(label);
        deleteContainer.appendChild(deleteButton);

        uploadItem.appendChild(inputContainer);
        uploadItem.appendChild(thumbnailContainer);
        uploadItem.appendChild(deleteContainer);

        form.appendChild(uploadItem);
        inputCount++;
    }

    function deleteInput(rowId) {
        const row = document.getElementById(rowId);
        if (row) {
            row.remove();
        }

        // Asegurar que al menos quede un input vacío
        const inputs = document.querySelectorAll('input[type="file"]');
        if (inputs.length === 0) {
            createNewFileInput();
        }
    }

    // Mejorar la experiencia del usuario con animaciones
    document.addEventListener('DOMContentLoaded', function() {
        // Animar la aparición de las tarjetas de tareas
        const taskCards = document.querySelectorAll('.task-card');
        taskCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
</script>
@endsection
