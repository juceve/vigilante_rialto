<div>
    @section('title')
    CUBRE RELEVOS
    @endsection
    <div class="row mb-3">
        <div class="col-1">
            <a href="{{ route('home') }}" class="text-silver"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
        </div>
        <div class="col-10">
            <h4 class="text-secondary text-center">ACTIVAR RELEVO</h4>
        </div>
        <div class="col-1"></div>

    </div>
    <div class="container">
        <div class="form-group{{ $errors->has('cliente_id') ? ' has-error' : '' }} mb-3">
            {!! Form::label('cliente_id', 'Cliente') !!}
            {!! Form::select('cliente_id',$clientes, null, ['id' => 'cliente_id','wire:model' =>
            'cliente_id','placeholder' =>
            'Seleccione un cliente',
            'class' => 'form-select']) !!}
            <small class="text-danger">{{ $errors->first('cliente_id') }}</small>
        </div>

        <div class="form-group{{ $errors->has('turno_id') ? ' has-error' : '' }} mb-3">
            {!! Form::label('turno_id', 'Turno') !!}
            <select name="turno_id" wire:model.defer="turno_id" class="form-select">
                <option value="">Seleccione un Turno</option>
                @if ($cliente->turnos->count())
                @foreach ($cliente->turnos as $item)
                <option value="{{$item->id}}">{{$item->nombre}}</option>
                @endforeach

                @endif

            </select>

            <small class="text-danger">{{ $errors->first('turno_id') }}</small>
        </div>
        <div class="d-grid mt-5">
            <button class="btn btn-primary py-3" wire:click='activarRelevo'>
                ACTIVAR RELEVO <i class="fas fa-toggle-on"></i>
            </button>
        </div>
    </div>

</div>