<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kardex</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/bs3/bootstrap.min.css') }}">

    <style>
        /* Forzar márgenes de página en dompdf para controlar exactamente la distancia */
        @page {
            margin: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            color: #23272f;
            background: #fff;
            margin: 0;
        }

        /* Aumentado padding en la primera página (.contenido) */
        .contenido {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            min-height: 75%;
            background: rgba(255, 255, 255, 0.8);
            z-index: -1;
            padding: 1cm;
            box-sizing: border-box;
        }

        /* Alinear el header/row de la primera página con los paneles (mismo left offset),
           y evitar que el contenido principal haga overflow en el eje X. */
        .contenido .row.header-align {
            width: 100%;
            margin-left: 22px;
            /* coincide con los paneles que usan margin-left: 22px */
            margin-right: 22px;
            /* evita que el contenido llegue hasta el borde derecho */
            box-sizing: border-box;
        }

        .document-footer {
            position: fixed;
            bottom: 20px;
            left: 0;
            right: 0;
            font-size: 10px;
            color: #555;
            text-align: center;
            border-top: 1px solid #aaa;
            padding-top: 5px;
        }

        /* .manual-page-border: borde visible colocado a 1cm del borde de la hoja */
        .manual-page-border {
            margin: 1cm;
            /* distancia del borde de la página hasta el borde visible */
            border: 1px solid #333;
            /* borde visible */
            box-sizing: border-box;
            width: auto;
            height: auto;
        }

        .manual-page {
            margin: 0;
            padding: 0.3cm;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
            line-height: 1.25;
            color: #222;
            word-wrap: break-word;
            overflow-wrap: break-word;
            box-sizing: border-box;
        }

        /* Evitar que la .row de Bootstrap sobresalga por los márgenes negativos dentro de la manual-page
           y reducir el espacio (gutter) entre columnas. */
        .manual-page .row.no-gutter {
            margin-left: 0;
            margin-right: 0;
        }

        .manual-page .row.no-gutter>[class*="col-"] {
            padding-left: 4px;
            padding-right: 4px;
        }

        .manual-page-border {
            overflow: hidden;
        }

        .manual-title {
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .manual-section {
            margin-bottom: 8px;
            text-align: justify;
        }

        .manual-footer {
            margin-top: 10px;
            font-size: 8px;
            color: #666;
            text-align: center;
        }

        /* asegurar salto de página antes (compatible con dompdf) */
        .page-break {
            page-break-before: always;
        }
    </style>


</head>

<body>

    <div class="contenido">

        <div class="row header-align">
            <div class="col-xs-5">
                <br>
                <small>
                    <strong>
                        {{ strtoupper(config('app.name')) }} <br>
                        Seguridad Privada y Vigilancia <br>

                        SANTA CRUZ - BOLIVIA
                    </strong>
                </small>
            </div>

            <div class="col-xs-3 text-right">

            </div>
            <div class="col-xs-4 text-center">
                <img class="img-responsive" src="{{ asset(config('adminlte.auth_logo.img.path')) }}"
                    style="width: 60px; margin-top: 1rem">
            </div>
        </div>

        <h4 class="text-center text-primary " style="margin-left: 22px;">
            <div class="alert alert-info" role="alert">FICHA DE EMPLEADO</div>
        </h4>

        <div class="panel panel-primary" style="font-size: 14px; margin-top: 10px; margin-left: 22px;">
            <div class="panel-heading text-center">
                <h3 class="panel-title">INFORMACIÓN PERSONAL</h3>
            </div>
            <div class="panel-body">
                <div style="font-size:0;">
                    <div class="text-center"
                        style="display:inline-block; width:20%; vertical-align:top; font-size:13px; ">
                        @if ($empleado->imgperfil)
                            <img src="{{ Storage::url($empleado->imgperfil) }}" class="img-responsive"
                                style="max-width:110px; max-height:110px;border:1px solid #337ab7; border-radius:8px; padding: 1rem; object-fit:cover;">
                        @else
                            <img src="{{ asset('images/no-perfil2.jpg') }}" class="img-responsive"
                                style="width: 110px;border:1px solid #337ab7; border-radius:8px; padding: 1rem">
                        @endif
                    </div>
                    <div
                        style="display:inline-block; width:77%; margin-left: 15px; vertical-align:top; font-size:12px;">
                        <div class="row">
                            <div class="col-xs-6">

                                <p>
                                    <strong>Nombres: </strong> {{ $empleado->nombres }}
                                </p>
                                <p>
                                    <strong>Nro. Doc.: </strong> {{ $empleado->cedula }}
                                </p>
                                <p>
                                    <strong>Fecha Nacimiento: </strong> {{ formatearFecha($empleado->fecnacimiento) }}
                                </p>
                                <p>
                                    <strong>Dirección: </strong> {{ $empleado->direccion }}
                                </p>
                                <p>
                                    <strong>Email: </strong> {{ $empleado->email }}
                                </p>
                            </div>
                            <div class="col-xs-6">
                                <p>
                                    <strong>Apellidos: </strong> {{ $empleado->apellidos }}
                                </p>
                                <p>
                                    <strong>Tipo Doc.: </strong> {{ $empleado->tipodocumento->name }}
                                </p>
                                <p>
                                    <strong>Nacionalidad: </strong> {{ $empleado->nacionalidad }}
                                </p>
                                <p>
                                    <strong>Teléfono: </strong> {{ $empleado->telefono }}
                                </p>
                                <p>
                                    <strong>Usuario Sistema: </strong>
                                    {{ $empleado->user_id ? 'Generado' : 'No generado' }}
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <div class="panel panel-primary" style="font-size: 12px; margin-top: 10px; margin-left: 22px;">
            <div class="panel-heading text-center">
                <h3 class="panel-title">INFORMACIÓN LABORAL</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-6">

                        <p>
                            <strong>Area: </strong> {{ $empleado->area->nombre }}
                        </p>
                        @if ($contrato)
                            <p>
                                <strong>Inicio: </strong> {{ formatearFecha($contrato->fecha_inicio) }}
                            </p>
                            <p>
                                <strong>Tipo Contrato: </strong> {{ $contrato->rrhhtipocontrato->nombre }}
                            </p>
                            <p>
                                <strong>Salario: </strong> {{ $contrato->salario_basico }}
                            </p>
                            <p>
                                <strong>Gestora: </strong> {{ number_format($contrato->gestora, 2, '.') }}
                            </p>
                        @endif
                    </div>
                    <div class="col-xs-6">
                        <p>
                            <strong>Nro. Contrato.: </strong> {{ $contrato ? cerosIzq($contrato->id) : 'Sin definir' }}
                        </p>
                        @if ($contrato)
                            <p>
                                <strong>Final: </strong>
                                {{ $contrato->fecha_fin ? formatearFecha($contrato->fecha_fin) : 'Indefinido' }}
                            </p>
                            <p>
                                <strong>Cargo: </strong> {{ $contrato->rrhhcargo->nombre }}
                            </p>
                            <p>
                                <strong>Moneda: </strong> {{ $contrato->moneda }}
                            </p>
                            <p>
                                <strong>Caja/Seguro: </strong> {{ $contrato->caja_seguro ? 'SI' : 'NO' }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <div class="panel panel-primary" style="font-size: 12px; margin-top: 10px; margin-left: 22px;">
            <div class="panel-heading text-center">
                <h3 class="panel-title">DATOS DE REFERENCIA Y MÉDICOS</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-6">

                        <p>
                            <strong>Persona de Referecia: </strong> {{ $empleado->persona_referencia ?? '-' }}
                        </p>
                        <p>
                            <strong>Parentezco: </strong> {{ $empleado->parentezco_referencia ?? '-' }}
                        </p>
                    </div>
                    <div class="col-xs-6">
                        <p>
                            <strong>Telf. Referencia: </strong> {{ $empleado->telefono_referencia ?? '-' }}
                        </p>

                    </div>
                </div>
                <div class="alert alert-warning" role="alert">
                    <div class="row">
                        <div class="col-xs-6">

                            <p>
                                <strong>Padece Enfermedad: </strong><br>{{ $empleado->enfermedades ?? 'N/A' }}
                            </p>

                        </div>
                        <div class="col-xs-6">
                            <p>
                                <strong>Padece Alergias: </strong><br>{{ $empleado->alergias ?? 'N/A' }}
                            </p>

                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    {{-- <div class="document-footer">
        Documento confidencial - {{ config('app.name', 'Sistema de Gestión') }}<br>
        Impreso el {{ now()->format('d/m/Y \a \l\a\s H:i') }}
    </div> --}}
    <div class="page-break"></div>

    {{-- PAGINA 2 --}}

    <div class="manual-page-border">
        <div class="manual-page">
            <div class="manual-title text-center" style="font-size: 12px;">
                MANUAL DE NORMAS Y FUNCIONES DEL PERSONAL OPERATIVO <br>
                (guardias de seguridad)
            </div>
<br>
            <div class="manual-section">
                <strong>NOMBRE DEL CARGO: AGENTE DE SEGURIDAD - DEPENDENCIA: AREA OPERATIVA <br>
                    CARGO DEL JEFE INMEDIATO: SUPERVISOR - REPORTA A: SUPERVISOR , JEFE OPERATIVO Y SECRETARIA</strong>
            </div>
<br>
            <div class="manual-section">
                1.- Efectuar el orden y el aseo al inicio de la jornada de trabajo para un mejor ambiente en la
                ejecución de labores. <br>
                2.- Cumplir con las órdenes de organización del supervisor, jefe operativo y secretaria. <br>
                3.- Lleva a cabo el procedimiento necesario, para cumplir con los objetivos del área operativa
                explicados y detallados por el supervisor y el jefe operativo. <br>
                4.- Realiza solicitudes, quejas y peticiones al supervisor y jefe operativo. <br>
                5.- Horario de trabajo es de lunes a domingo de 07:00 a.m. a 19:00 p.m. y/o viceversa. <br>
                6.- Llegar al lugar de trabajo en forma adecuada lo cual significa no estar en esta de ebriedad o bajo
                alguna sustancia o estupefaciente. <br>
                7.- Queda prohibido abandonar el puesto de trabajo sin que sea relevado por el agente entrante salvo
                autorización del supervisor, de la secretaria o administración. <br>
                8.- Queda prohibida la agresión tanto verbal como física a algún miembro de la empresa RIALTO PATROL
                como a sus superiores. <br>
                9.- Realizar su reporte verbal diario para el jefe operativo, secretaria general y supervisor. <br>
                10.- Cumplir con las órdenes del supervisor, jefe de operaciones y secretaria general teniendo como
                finalidad cumplir con los objetivos y metas de la empresa. <br>
                11.- Es responsabilidad del agente de seguridad el cuidado y preservación del material que se le asigne
                para la realización de su trabajo así como también los predios que se le asignen para la vigilancia y
                cuidado. <br>
                12.- En caso de pérdida o deterioro del material asignado para el desarrollo de sus funciones (handys,
                correajes, telefonos corporativos, libros de asistencia y otros registros, bicicletas, ponchos, toletes,
                silvatos, dispositivos de control de rondas, chalecos, etc) será descontado del pago correspondiente
                para su reposición. <br>
                13.- Llenar libro de novedades correctamente con lo más relevante ocurrido en la jornada. así como el
                libro de registro de ingreso y salida de vehículos. <br>
                14.- Llenar el libro de asistencia y firmar el mismo (Nombre completo, hora y firma).
                15.- Reportar su hora de ingreso y salida con el Jefe de Operaciones con su Supervisor a cargo y OFICINA
                CENTRAL mediante telefono corporativo. <br>
                16.- Según el puesto en el que trabajen se debe cumplir correctamente las funciones específicas
                encomendadas. <br>
                17.- Estar limpios íntegramente (Bañados, bien recortados, Peinados, sin Aros, etc.) <br>
                18.- Ser respetuosos y proactivos en el trabajo todo momento. <br>
                19.- No faltarse injustificadamente caso contrario sera sancionado. tres faltas continuas implica su
                retiro automatico. <br>
                20.- No dormirse en horarios de trabajo, no utilizar el teléfono de celular para redes sociales en su
                jornada laboral. <br>
                21.- Colaborar al Supervisor haciendo extras al menos 1 vez por semana para cubrir turno de libres
                donde el Supervisor lo requiera, este extra será remunerado
                economicamente. <br>
                22.- No consumir bebidas alcohólicas ni sustancias controladas dentro del área labral, lo cual supondra
                su sancion y retiro inmediato. <br>
                23.- Si tienen alguna duda o reclamo comunicarse con Administración de la Empresa Rialto Patrol a la
                cual pertenecen y no así al lugar donde realizan su trabajo
                porque estarían infringiendo las reglas. <br>
                24.- En caso de que la empresa sea afectada en su integridad empresarial como en su imagen por causa de
                un mal procedimiento o descuido de sus funciones como
                agente de seguridad y esto a la vez genere pérdida parcial o total del contrato, el trabajador será
                despedido de forma inmediata sin derecho a ningún pago ni
                beneficios sociales. <br>
                La empresa se reserva el derecho de seguir las acciones tantos civiles como penales si se llega a
                comprobar alguna responsabilidad del agente de seguridad.
                25.- El agente acepta trabajar como minimo durante treinta dias en la empresa. <br>
                <span style="color: red">
                    26.- El pago de los beneficios sociales (aguinaldo, liquidacion,vacaciones...) se hará en base al
                    salario minimo nacional decretado por el gobierno nacional.
                </span> <br>
                27.- En caso de perdida o robo de algun objeto de valor en el puesto de trabajo, por negligencia, el
                agente se hará cargo de la reposición de dicha pérdida. <br>
                28.- En caso de retiro voluntario, el agente debe entregar en la oficina central una carta escrita
                especificando los motivos. esta carta debe ser entregada con 15 días
                habiles de anticipación, si usted se retira sin previo aviso será tomado como falta y automaticamente
                será sancionado de acuerdo a lo que dicta en el punto nro. 18,
                además se dará parte al ministerio de trabajo. <br>
                29.- En caso de retiro debe devolver todo el material perteneciente a la empresa, caso contrario se le
                retendrá un monto económico hasta que se efectue la devolución. <br>
                30.- En caso de que se encuentre al personal in fraganti distribuyendo o cunsumiendo substancias
                controladas como ser : marihuana, cocaina, pasta base, etc., seran
                denunciados por la empresa y seran procesados penalmente segun ley 1008, ante la Policía Nacional FELCN.
                <br>
                31.- Queda prohibido las relaciones amistosas con propietarios y con personal de administracion en
                horario laboral. <br>
                32.- Proporcionar toda la documentación actualizada como ser: c.i., antecedentes FELCC y FELCN en
                cualquier momento que la empresa lo requiera. <br>
                33.- Asistir a las reuniones a las que sea convocado. <br>
                34.- Asistir a las capacitaciones o cursos. <br>
                35.- Debe adoptar la rotación, esto implica: turno dia o noche o asignación en un nuevo lugar de trabajo
                diferente al puesto al que fue contratado inicialmente. <br>
                36.- No recibir dinero de parte de los clientes, para guardar o ser entregado a otras personas. <br>
                37.- No recibir Objetos de valor de parte de los clientes, para guardar o ser entregado a otras
                personas.<br>
                38.- No dejar ingresar a personas extrañas a los predios custodiados sin autorización del cliente o
                clientes.<br>
            </div>
            <br><br>
            <div class="manual-title text-center" style="font-size: 12px;">
                SANCIONES
            </div> <br>
            <div class="manual-section">
                <div class="row no-gutter">
                    <div class="col-xs-4">
                        1.- Abandono de puesto equivalente a una falta <br>
                        4.- No reportar ingreso y salida a la oficina central <br>
                        7.- Ocasionar daños a los teléfonos corporativos <br>
                        10.- Faltarse injustificadamente <br>
                        13.- Poner un reemplazo sin autorización <br>
                    </div>
                    <div class="col-xs-4">
                        2.- Llegar tarde al puesto <br>
                        5.- No registrar correctamente el libro de novedades <br>
                        8.- Dañar o pérder chips corporativos <br>
                        11.- No llevar una buena higiene personal <br>
                    </div>
                    <div class="col-xs-4">
                        3.- No portar correctamente el uniforme <br>
                        6.- Dormir en horas de trabajo <br>
                        9.- No portar el credencial <br>
                        12.- Portar el uniforme sucio o en mal estado <br>
                    </div>
                </div>
            </div>
            <br><br><br>
            <div class="manual-section">
                <div class="row no-gutter">
                    <div class="col-xs-5">
                        <br>
                        *a continuacion firmo como constancia a mi conformidad con <br>
                        todos los puntos expuestos en la presente, y me comprometo <br>
                        a cumplir con lo estipulado
                    </div>
                    <div class="col-xs-7" style="font-size: 13px;">
                        <strong>Firma Empleado: &nbsp;&nbsp;&nbsp;&nbsp;_______________________________</strong> <br><br>
                        <strong>Nombre Completo: &nbsp;_______________________________</strong> <br><br>
                        <strong>C.I.: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_______________________________</strong> <br>
                    </div>

                </div>
            </div>
<br><br>
<br>

            <div class="manual-footer">
               {{ strtoupper(config('app.name')) }} • Impreso el
                {{ now()->format('d/m/Y \a \l\a\s H:i') }}
            </div>
        </div>
    </div>

    <script src="{{ asset('vendor/bs3/bootstrap.min.js') }}"></script>
</body>

</html>
