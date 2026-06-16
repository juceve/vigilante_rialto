<div class="form-group">
    {!! Form::label('name', 'Nombre') !!}
    {!! Form::text('name', null, ['class'=>'form-control', 'placeholder' => 'Nombre del Rol']) !!}
    @error('name')
    <small class="text-danger">{{$message}}</small>
    @enderror

    <h2 class="h3 mt-3 mb-2">Lista de Permisos</h2>
    <button type="button" class="btn btn-info mb-2" style="width: 250px" onclick="selectAll()">Seleccionar
        todos</button>
    <button type="button" class="btn btn-secondary mb-2" style="width: 250px" onclick="unSelectAll()">No seleccionar
        nada</button>
    @php
    $grupo="";
    @endphp

    <div class="container-fluid mb-2">
        <div class="row mt-3">
            @foreach ($permissions as $permission)

            @if($grupo == $permission->grupo)

            <div class="col-12 form-group p-2">
                <div class="ml-3">
                    {!! Form::checkbox('permissions[]', $permission->id, null, ['class'=>'mr-1']) !!}
                    {{$permission->descripcion}}
                </div>
            </div>
            @else
            @if ($grupo=="")
            <div class="col-12 col-md-3 border">
                <div class="row gx-3">
                    @else
                </div>
            </div>
            <div class="col-12 col-md-3 border">
                <div class="row gx-3">
                    @endif
                    @php
                    $grupo = $permission->grupo;
                    @endphp
                    <div class="col-12 form-group p-2 text-center bg-info"><label for="">{{$permission->grupo}}</label>
                    </div>
                    <div class="col-12 form-group p-2">
                        <div class="ml-3">
                            {!! Form::checkbox('permissions[]', $permission->id, null, ['class'=>'mr-1']) !!}
                            {{$permission->descripcion}}
                        </div>

                    </div>

                    @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>