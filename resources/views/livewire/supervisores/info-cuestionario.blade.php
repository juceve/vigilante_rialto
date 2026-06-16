<div style="margin-top: 95px">
    @section('title')
        Datos Checklist
    @endsection

    <div class="alert alert-primary" role="alert" style="font-size: 16px;">
        <div class="row">
            <div class="col-2">
                <a href="{{ route('supervisores.listadosupervisiones', $inspeccionActiva->id) }}"
                    class="btn btn-secondary rounded-circle d-flex align-items-center justify-content-center"
                    style="width:45px; height:45px;">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <div class="col-10">
                <div class="text-secondary">
                    <span title="Titulo Cuestionario">
                        <i class="fas fa-clipboard"></i>
                        <strong>{{ $cuestionarioEjecutado->chklListaschequeo->titulo }}</strong>
                    </span> <br>
                    <i class="fas fa-calendar-alt"></i> {{ formatearFecha($cuestionarioEjecutado->fecha) }} |
                    <i class="fas fa-clock"></i> {{ substr($cuestionarioEjecutado->created_at, 10) }} <br>
                    <i class="fas fa-user-secret"></i>
                    {{ $cuestionarioEjecutado->empleado->nombres . ' ' . $cuestionarioEjecutado->empleado->apellidos }}
                </div>
            </div>
        </div>

    </div>
    <div class="container">
        <div class="card">
            <div class="card-header bg-primary text-white">
                RESUMEN
            </div>
            <div class="card-body">
                @php
                    $i = 0;
                @endphp
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <tbody>
                            @foreach ($cuestionarioEjecutado->chklRespuestas as $respuesta)
                                <tr>
                                    <td>
                                        <strong>
                                            {{ ++$i }}.- {{$respuesta->chklPregunta->descripcion}} <br>
                                        </strong>
                                        <div id="respuestas{{ $respuesta->id }}"
                                            style="font-size: 13px; margin-left: 25px;">
                                            <span class="text-{{ $respuesta->ok ? 'primary' : 'danger' }}">
                                                CUMPLIMIENTO: <strong>{{ $respuesta->ok ? 'SI' : 'NO' }}</strong>
                                            </span>
                                            @if ($respuesta->chklIncumplimientos->count() > 0)
                                                <br>
                                                <strong>Infractores:</strong>
                                                <ul>


                                                    @foreach ($respuesta->chklIncumplimientos as $incumplimiento)
                                                        <li>{{$incumplimiento->empleado->nombres . ' ' . $incumplimiento->empleado->apellidos}}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                                @if (!is_null($respuesta->chklPregunta->tipoboleta_id) && $respuesta->ok == 0)
                                                    <div class="alert alert-danger" role="alert">
                                                        {{-- <span class="text-{{ $respuesta->ok ? 'primary' : 'danger' }}"> --}}
                                                            <strong>Aplica Sación:</strong>
                                                            {{ $respuesta->chklPregunta->tipoboleta->nombre }} <strong>[Bs.:
                                                                {{ $respuesta->chklPregunta->tipoboleta->monto_descuento }}]</strong>
                                                            {{-- </span> --}}
                                                    </div>
                                                @endif

                                            @endif



                                            @if (!is_null($respuesta->observacion))
                                                <div class="alert alert-info" role="alert">
                                                    <strong>Observaciones:</strong> {{ $respuesta->observacion }}
                                                </div>
                                            @endif
                                        </div>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if (!is_null($cuestionarioEjecutado->notas))
                    <div class="alert alert-warning" role="alert">
                        <strong>Notas del Checklist:</strong> {{ $cuestionarioEjecutado->notas }}
                    </div>
                @endif
            </div>
        </div>
        <div class="d-grid mt-3 mb-2">
            <a href="{{ route('supervisores.listadosupervisiones', $inspeccionActiva->id) }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Volver</a>
        </div> <br>
    </div>
</div>
