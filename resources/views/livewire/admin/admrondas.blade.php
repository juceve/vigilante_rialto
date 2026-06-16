<div>
    @section('title')
        Registro de Rondas
    @endsection
    @section('content_header')
        <h4>Registro de Rondas</h4>
    @endsection

    <div class="container-fluid">
        <div class="card">
            <div class="card-header bg-success text-white">
                Generar Reporte de Rondas
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col col-12 col-md-3" wire:ignore.self>
                        <select class="form-control" style="width: 100%;" wire:model='operador'>
                            <option value="">Seleccione un Operador</option>
                            @foreach ($operadores as $operador)
                                <option value="{{ $operador['id'] }}">{{ $operador['nombre'] }}</option>
                            @endforeach
                        </select>
                        @error('operador')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col col-12 col-md-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Inicio</span>
                            </div>
                            <input type="date" value="{{ date('Y-m-d') }}" class="form-control" wire.model="fechaI"
                                aria-describedby="basic-addon1">
                        </div>
                        @error('fechaI')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="col col-12 col-md-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Final</span>
                            </div>
                            <input type="date" value="{{ date('Y-m-d') }}" class="form-control" wire.model="fechaF"
                                aria-describedby="basic-addon1">
                        </div>
                        @error('fechaF')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>

                    <div class="col col-12 col-md-3">
                        <button class="btn btn-primary btn-block" wire:click='buscar'><i class="fas fa-search"></i>
                            Buscar</button>
                    </div>

                </div>

                {{-- @if ($resultados)
                    @dump($resultados)
                @endif --}}
            </div>
        </div>
    </div>
</div>
@section('js')
@endsection
