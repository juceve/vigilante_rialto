<div>
    @section('title')
    Registro de Usuario para Clientes
    @endsection
    @section('content_header')
    <div class="container-fluid">
        <h4>Registro de Usuario para Clientes</h4>
    </div>
    @endsection

    <div class="container-fluid">
        <div class="card">
            <div class="card-header bg-info">
                <div style="display: flex; justify-content: space-between; align-items: center;">

                    <span id="card_title">
                        CLIENTE: {{$cliente->nombre}}
                    </span>

                    <div class="float-right">
                        <a href="javascript:history.back()" class="btn btn-info btn-sm float-right"
                            data-placement="left">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if (!$usercliente)
                <div class="form-group">
                    <label>Email Cliente:</label>
                    <input type="email" class="form-control" wire:model.defer='email' @if ($email) readonly @endif>
                    @error('email')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Password: <small>(Nro. Documento del Cliente)</small></label>
                    <input type="text" class="form-control" wire:model.defer='password' readonly>
                    @error('password')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" wire:click='registrar'>CREAR USUARIO</button>
                </div>
                @else
                <div class="form-group">
                    <label>El usuario ya fue generado:</label><br>
                    <span><strong>Usuario: </strong>{{$usercliente->user->email??''}}</span><br>
                    <span><strong>Password: </strong>[<i>Nro. de Documento del Cliente</i>]</span><br><br>
                    <button class="btn btn-danger" onclick='eliminar({{$cliente->id}})'>Eliminar usuario <i
                            class="fas fa-trash"></i></button>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
@section('js')
<script>
    function eliminar(id){
    Swal.fire({
  title: "ELIMINAR USUARIO",
  text: "Está seguro de realizar esta operación?",
  icon: "warning",
  showCancelButton: true,
  confirmButtonColor: "#3085d6",
  cancelButtonColor: "#d33",
  confirmButtonText: "Sí, continuar",
  cancelButtonText: "No, cancelar",
}).then((result) => {
  if (result.isConfirmed) {
    Livewire.emit('eliminar',id);
  }
});
}
</script>
@endsection
