<div>
    <style>
        /* Badge animada estilo “policía” para Ronda en Proceso */
        .ronda-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            font-weight: 600;
            color: #e7d90f;
            background: linear-gradient(270deg, #dc2626, #3b82f6, #609bfa, #3b82f6, #dc2626);
            background-size: 600% 600%;
            animation: ronda-gradient-police 5s ease infinite, ronda-pulse 3s infinite;
            text-decoration: none;
            transition: transform 0.2s ease;
        }

        .ronda-badge:hover {
            transform: scale(1.1);
        }

        /* Gradiente animado estilo luces de policía */
        @keyframes ronda-gradient-police {
            0% {
                background-position: 0% 50%;
            }

            25% {
                background-position: 50% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            75% {
                background-position: 50% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Pulso sutil para resaltar */
        @keyframes ronda-pulse {

            0%,
            100% {
                box-shadow: 0 0 0px rgba(255, 255, 255, 0.4);
            }

            50% {
                box-shadow: 0 0 12px rgba(255, 255, 255, 0.6);
            }
        }
    </style>

    @if ($rondaejecutada_id)
        <a href="{{ route('vigilancia.recorrido_ronda', $rondaejecutada_id) }}" class="ronda-badge">
            <img src="{{ asset('images/guardman.png') }}" alt="Guard" width="20" height="20">
            Ronda en Progreso
        </a>
    @endif

</div>
