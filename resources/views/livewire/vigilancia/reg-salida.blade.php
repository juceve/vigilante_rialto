@push('styles')
    <link rel="stylesheet" href="{{ asset('css/styleVigilancia.css') }}">
@endpush

<div>
    @section('title')
    SALIDAS
    @endsection

    <!-- Header Corporativo -->
    <div class="patrol-header">
        <div class="container">
            <div class="header-navigation">
                <a href="{{ route('vigilancia.panelvisitas',$designacion->id) }}" class="back-button">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="header-title">
                    <h1 class="title-text">REGISTRO DE SALIDAS</h1>
                    <p class="subtitle-text">Control de salida de visitantes</p>
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
                <i class="fas fa-building" style="margin-right: 10px"></i>
                <span>Cliente: {{ $designacion->turno->cliente->nombre }}</span>
            </div>
        </div>

        <!-- Lista de Visitas en Proceso -->
        <div class="visits-list-card">
            <div class="visits-list-header">
                <i class="fas fa-sign-out-alt"></i>
                <span>VISITAS EN PROCESO</span>
            </div>
            <div class="visits-list-body">
                <!-- Buscador -->
                <div class="search-section">
                    <label class="search-label">
                        <i class="fas fa-search"></i>
                        Búsqueda de visitantes:
                    </label>
                    <input type="search" class="search-input" placeholder="Buscar por nombre o cédula..."
                        wire:model.debounce.500ms='search' id="search">
                </div>

                <!-- Tabla de Visitas -->
                <div class="visits-table">
                    <div class="table-container">
                        <table class="corporate-table">
                            <thead>
                                <tr class="table-header">
                                    <th>VISITANTE</th>
                                    <th>CÉDULA</th>
                                    <th>INGRESO</th>
                                    <th>ACCIÓN</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($visitas as $item)
                                <tr class="table-row">
                                    <td class="cell-left">{{$item->visitante}}</td>
                                    <td class="cell-center">{{$item->docidentidad}}</td>
                                    <td class="cell-center">{{$item->fechaingreso. " ".$item->horaingreso}}</td>
                                    <td class="cell-center">
                                        <a href="{{route('salidavisita',$item->id)}}" class="action-btn view-btn"
                                            title="Registrar salida">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td class="empty-state" colspan="4">
                                        <i class="fas fa-inbox"></i>
                                        <span>No se encontraron visitantes en proceso</span>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- Cierre patrol-content -->
    <!-- Modal -->
    {{-- <div class="modal fade" id="modalInfo" tabindex="-1" aria-labelledby="modalInfoLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalInfoLabel">Info Visita</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        wire:click='reiniciar'></button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div> --}}
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

    /* Card de Lista de Visitas */
    .visits-list-card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-light);
        overflow: hidden;
        margin-bottom: 2rem;
        border: 2px solid var(--error-color);
    }

    .visits-list-header {
        background: linear-gradient(135deg, var(--error-color), #ef4444);
        color: white;
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        font-weight: 600;
        font-size: 1rem;
    }

    .visits-list-header i {
        margin-right: 0.75rem;
        font-size: 1.2rem;
    }

    .visits-list-body {
        padding: 2rem;
    }

    /* Sección de Búsqueda */
    .search-section {
        margin-bottom: 2rem;
    }

    .search-label {
        display: flex;
        align-items: center;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--on-surface);
        margin-bottom: 0.5rem;
    }

    .search-label i {
        margin-right: 0.5rem;
        color: var(--error-color);
        width: 16px;
    }

    .search-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: calc(var(--border-radius) / 2);
        font-size: 0.9rem;
        transition: var(--transition);
        background: white;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--error-color);
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    /* Tabla de Visitas */
    .visits-table {
        background: white;
        border-radius: calc(var(--border-radius) / 2);
        overflow: hidden;
        box-shadow: var(--shadow-light);
        border: 1px solid #e2e8f0;
    }

    .table-container {
        overflow-x: auto;
    }

    .corporate-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }

    .table-header {
        background: linear-gradient(135deg, var(--secondary-color), var(--secondary-dark));
        color: white;
    }

    .table-header th {
        padding: 1rem 0.75rem;
        font-weight: 600;
        text-align: center;
        border: none;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    .table-row {
        transition: var(--transition);
        border-bottom: 1px solid #f1f5f9;
    }

    .table-row:hover {
        background-color: #fef2f2;
        transform: translateY(-1px);
    }

    .table-row td {
        padding: 1rem 0.75rem;
        border: none;
        vertical-align: middle;
    }

    .cell-center {
        text-align: center;
    }

    .cell-left {
        text-align: left;
        font-weight: 500;
    }

    /* Botón de Acción */
    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        text-decoration: none;
        transition: var(--transition);
        border: none;
        cursor: pointer;
        font-size: 0.9rem;
    }

    .view-btn {
        background: linear-gradient(135deg, var(--warning-color), var(--accent-light));
        color: white;
    }

    .view-btn:hover {
        background: linear-gradient(135deg, #b45309, var(--warning-color));
        transform: scale(1.1);
        color: white;
        box-shadow: var(--shadow-medium);
    }

    /* Estado Vacío */
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--text-secondary);
        font-style: italic;
    }

    .empty-state i {
        display: block;
        font-size: 3rem;
        margin-bottom: 1rem;
        color: #cbd5e1;
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

        .visits-list-body {
            padding: 1.5rem 1rem;
        }

        .table-header th {
            padding: 0.75rem 0.5rem;
            font-size: 0.75rem;
        }

        .table-row td {
            padding: 0.75rem 0.5rem;
            font-size: 0.8rem;
        }

        .corporate-table {
            font-size: 0.8rem;
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
        .visits-list-header {
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
        }

        .assignment-body,
        .visits-list-body {
            padding: 1rem 0.75rem;
        }

        .search-input {
            padding: 0.625rem 0.75rem;
            font-size: 0.85rem;
        }

        .table-header th {
            padding: 0.5rem 0.25rem;
            font-size: 0.7rem;
        }

        .table-row td {
            padding: 0.5rem 0.25rem;
            font-size: 0.75rem;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            font-size: 0.8rem;
        }

        .corporate-table {
            font-size: 0.75rem;
        }
    }
</style>
@endsection

@section('js')
<script>
    document.getElementById('search').focus();
</script>
@endsection
