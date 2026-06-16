@extends('adminlte::page')

@section('title')
Nuevo Usuario |
@endsection

@section('content')
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">

            @includeif('partials.errors')

            <div class="card card-info">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">

                        <span id="card_title">
                            Nuevo Usuario
                        </span>

                        <div class="float-right">
                            <a href="{{ route('users.index') }}" class="btn btn-info btn-sm float-right"
                                data-placement="left">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('users.store') }}" role="form" enctype="multipart/form-data">
                        @csrf

                        @include('user.form')

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('js')
<script>
    function togglePasswordVisibility(inputId) {
        var input = document.getElementById(inputId);
        var type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
        
        // Cambiar el ícono de ojo abierto/cerrado
        this.event.currentTarget.classList.toggle('fa-eye');
        this.event.currentTarget.classList.toggle('fa-eye-slash');
    }
</script>
@endsection