{{-- filepath: c:\laragon\www\vigilantev2\resources\views\livewire\vigilancia\alerta-hv.blade.php --}}
<div wire:poll.60s="verificarPendientes">
    @if($tienePendientes && $intervaloActual)
        <a href="{{ route('vigilancia.hombre-vivo', $intervaloActual['id']) }}"
           class="btn btn-link position-relative p-2"
           title="Marcar Hombre Vivo">
            <i class="fas fa-heartbeat fa-lg text-{{ $intervaloActual['urgente'] ? 'danger' : 'warning' }} pulse"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-{{ $intervaloActual['urgente'] ? 'danger' : 'warning' }}">
                1
                <span class="visually-hidden">pendiente</span>
            </span>
        </a>
    @else
        <button type="button" class="btn btn-link p-2" disabled>
            {{-- <i class="fas fa-heartbeat fa-lg text-muted opacity-50"></i>    --}}
        </button>
    @endif
</div>

@push('styles')
<style>
    .bell-ring {
        animation: ring 2s ease-in-out infinite;
    }

    @keyframes ring {
        0%, 100% { transform: rotate(-10deg); }
        50% { transform: rotate(10deg); }
    }
</style>
@endpush
