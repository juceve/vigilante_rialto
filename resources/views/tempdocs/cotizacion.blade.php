<!DOCTYPE html>
<html lang="es">

<?php
$data = decodGet($data);
$myArray = explode('^', $data);

$datos1 = explode('|', $myArray[0]);

$citecotizacion;
$detalles;
$datos = [];
$valido_hasta = '';

$encryptedId = '';
if ($datos1[0] != 0) {
    $cite_id = $datos1[0];

    $encryptedId = Crypt::encrypt($cite_id);
    $link = url('/') . '/formulario-cotizacion' . '/' . $encryptedId;
    $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($link) . '&size=130x130';

    $citecotizacion = traeCitecotizacion($cite_id);
    $detalles = traeDetallesCotizacion($cite_id);
    $datos = [$citecotizacion['cite'], $citecotizacion['fechaliteral'], $citecotizacion['destinatario'], $citecotizacion['cargo'], $citecotizacion['monto']];
    $valido_hasta = fechaEs(date('Y-m-d', strtotime($citecotizacion['fecha'] . ' +10 day')));
} else {
    $datos = explode('|', $myArray[1]);
    $detalles = $data_detalles;
}

?>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>COTIZACIÓN</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/bs3/bootstrap.min.css') }}">

    <style>
        body {
            background-size: initial;
            background-image: url("{{ $datos1[0] ? ($citecotizacion['estado'] ? asset('images/logo_shield.png') : asset('images/anulado.png')) : asset('images/copia.jpg') }}");
            background-position: center center;
            background-repeat: no-repeat;
            height: 100%;
        }

        .contenido {
            min-height: 95%;
            background: rgba(255, 255, 255, 0.9);
            z-index: -1;
        }
    </style>

</head>

<body>

    <div class="contenido">

        <section style="text-align: center; height: 100%; background: rgba(255, 255, 255, 1);" >
            <br><br>
            <img src="{{ asset('images/logohd.png') }}" style="width: 40%;">
            <br><br>
            <div style="
    width: 75%;
    margin: 0 auto;
    margin-top: 2rem;
    text-align: center;
">
                <p
                    style="
    font-size: 28px;
    background-color: rgba(20, 60, 119, 0.8);
    border-radius: 10px;
    color: white;
    padding: 25px 20px;
">
                    <strong>
                        PROPUESTA COMERCIAL INTEGRAL DE SEGURIDAD FISICA Y PATRIMONIAL
                    </strong>
                </p>
                <br>
                <p
                    style="
    font-size: 25px;
    background-color: rgba(20, 60, 119, 0.8);
    border-radius: 10px;
    color: white;
    padding: 25px 20px;
">
                    <strong>
                        CLIENTE: {{ $datos[2] }}
                    </strong>
                </p>
                <br>
                <p
                    style="
    font-size: 21px;
    background-color: rgba(13, 89, 202, 0.8);
    border-radius: 10px;
    color: white;
    padding: 25px 20px;
">
                    SANTA CRUZ-BOLIVIA
                </p>
            </div>

        </section>
        <div style="page-break-after: always;"></div>
        <section>
            <div class="row" style="width: 100%;margin-right: 3rem;">
                <div class="col-xs-3 text-center">
                    <img class="img-responsive" src="{{ asset('images/logo_shield.png') }}" style="width: 90px;">
                    <h4>
                        {{ Str::upper(config('app.name')) }} <br>
                        <small style="font-size: 8.5px">SEGURIDAD PRIVADA Y VIGILANCIA</small>
                    </h4>



                </div>

                <div class="col-xs-3 text-right">

                </div>
                <div class="col-xs-4 text-center">

                </div>
            </div>
            <br>
            <p style="margin-left: 3rem">

                Santa Cruz, {{ $datos[1] }}
                <br> <br>
                Señores: <br>
                <strong>
                    {{ $datos[2] }} <br>
                    {{ $datos[3] }} <br>
                </strong>
                Presente.-
            </p>
            <h5 style="margin-left: 3rem;margin-right: 3rem; margin-top: 3rem;text-align: center;">
                <u>
                    <strong>REF.: CARTA DE PRESENTACION Y COTIZACION</strong>
                </u>
            </h5>
            <p style="margin-left: 3rem;margin-right: 3rem; margin-top: 2rem;text-align: justify;">


                De nuestra mayor consideración: <br><br>
                &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                En Rialto Patrol como empresa de seguridad física patrimonial con más de 15 años de experiencia,
                comprendemos que la seguridad en las instalaciones de {{ $datos[2] }} trasciende la vigilancia
                básica.
                Es imperativo garantizar la tranquilidad de sus residentes y proteger activos de alto valor. Nuestro
                enfoque
                se centra en blindar la experiencia residencial de lujo contra toda amenaza, manteniendo la discreción
                operativa y la excelencia.
            </p>
            <p style="margin-left: 3rem;margin-right: 3rem; margin-top: 2rem;text-align: justify;">
                La mitigación de riesgos en entornos de alta exclusividad requiere protocolos avanzados de Control de
                Acceso
                (CA) y Respuesta Táctica (RT). Rialto Patrol implementa sistemas de seguridad y monitoreo perimetral
                mediante CCTV o rondas establecidas mediante nuestro sistema de control de rondas con geolocalización
                remota, previniendo eficazmente la intrusión. Aseguramos la integridad de su perímetro a través de
                rondas
                dinámicas de disuasión y análisis predictivo de vulnerabilidades.
            </p>
            <p style="margin-left: 3rem;margin-right: 3rem; margin-top: 2rem;text-align: justify;">
                Nuestra propuesta de valor se fundamenta en tres pilares innegociables que
                nos distinguen en el mercado:
                <br> <br>
                <strong>SELECCIÓN Y RIGOR HUMANO</strong>
                <br> <br>
                Implementamos procesos de reclutamiento, garantizando personal con perfiles psicológicos idóneos,
                antecedentes verificados y una formación continua en gestión de crisis y primeros auxilios.
                <br> <br>
                <strong>SUPERVISIÓN Y CONTROL OPERATIVO </strong>
                <br> <br>
                Contamos con un sistema de fiscalización 24/7 mediante unidades móviles de supervisión y tecnología de
                reporte en tiempo real, lo que garantiza que nuestras consignas se cumplan con precisión.
                <br> <br>
                <strong>CUMPLIMIENTO LEGAL Y SOCIAL </strong>
                <br> <br>
                Rialto Patrol SRL opera bajo estricto cumplimiento de la normativa laboral y legal vigente, brindando a
                nuestros clientes el respaldo de una empresa formal que asume la responsabilidad total sobre su
                contingente
                humano.
                <br> <br>
                La inacción en materia de seguridad puede comprometer la reputación y la paz de {{ $datos[2] }} le
                instamos a
                coordinar una visita técnica sin coste, donde nuestros expertos ejecutarán un Análisis de Riesgos (AR)
                detallado de su infraestructura. Permítanos demostrar cómo la excelencia en seguridad se convierte en un
                activo intangible.
                <br> <br>
                Agradeciendo de antemano el tiempo dedicado a revisar nuestra presentación, quedo a la espera de sus
                noticias para coordinar una breve reunión o visita técnica.
                <br><br>
                Atentamente,
            </p>
            <p style="margin-left: 3rem;margin-right: 3rem; margin-top: 8rem;text-align: center;">
                @if ($encryptedId != '' && $datos1[1] > 0)
                <p style="margin-left: 3rem;margin-right: 3rem; margin-top: 2rem;text-align: center;">
                    <img src="{{ $qrUrl }}" alt="QR Code"><br>
                @else
                <p style="margin-left: 3rem;margin-right: 3rem; margin-top: 8rem;text-align: center;">
            @endif
                <br>
                <strong>
                    Ing. David Manzano Souza
                </strong>
                <br> REPRESENTANTE LEGAL <br> RIALTO PATROL SRL
            </p>

        </section>
        <div style="page-break-after: always;"></div>
        <section>
            <div class="row" style="width: 100%;margin-right: 3rem;">
                <div class="col-xs-3 text-center">
                    <img class="img-responsive" src="{{ asset('images/logo_shield.png') }}" style="width: 90px;">
                    <h4>
                        {{ Str::upper(config('app.name')) }} <br>
                        <small style="font-size: 8.5px">SEGURIDAD PRIVADA Y VIGILANCIA</small>
                    </h4>



                </div>

                <div class="col-xs-3 text-right">

                </div>
                <div class="col-xs-4 text-center">

                </div>
            </div>
            <p style="margin-left: 3rem;margin-right: 5rem; margin-top: 2rem;text-align: right;">
                <strong style="font-size: 18px; color: rgb(0, 94, 156)"><u>COTIZACIÓN</u></strong><br>
                <strong>Nro. de Documento: </strong> {{ $datos1[0] ? $datos[0] : '0000/00' }} <br>
                <strong>Fecha: </strong> {{ $datos[1] }} <br>
                <strong>Válido hasta: </strong> {{ $valido_hasta }} <br>

            </p>

            <br>
            <p style="margin-left: 5rem;margin-right: 5rem;">
                <strong>
                    CLIENTE: {{ $datos[2] }} <br>
                    {{ $datos[3] }} <br>
                </strong>
                ________________________________________________________________________________
            </p>
            <p style="margin-left: 5rem;margin-right: 5rem; text-align: justify;">
                <strong>1. CARTA DE PRESENTACIÓN</strong> <br><br>
                Estimado Cliente <br> <br>
                Entendemos que la seguridad de su patrimonio y su personal no es negociable.
                En Rialto Patrol, no solo ofrecemos personal uniformado; ofrecemos una
                estrategia de disuasión y protección activa.
                Basados en nuestro análisis preliminar de sus instalaciones, hemos diseñado la
                siguiente solución de seguridad a medida para mitigar riesgos y garantizar la
                continuidad de sus operaciones. <br>
                ________________________________________________________________________________
            </p>
            <p style="margin-left: 5rem;margin-right: 5rem; text-align: justify;">
                <strong>2. PROPUESTA DE VALOR (¿Por qué nosotros?) </strong> <br><br>
                A diferencia de la seguridad convencional, su servicio con Rialto Patrol incluye:

            </p>
            <p style="margin-left: 7rem;margin-right: 5rem; text-align: justify;">

                • Selección Rigurosa: Personal con antecedentes verificados. <br>
                • Reportes en Tiempo Real: Acceso a bitácora digital (vía app/web)
                para que usted sepa qué pasa en su empresa al instante. <br>
                • Supervisión Activa: Rondas aleatorias de nuestras unidades móviles
                de supervisión (sin costo extra). <br>
                • Capacitación Continua: Guardias entrenados en primeros auxilios,
                control de incendios y manejo de crisis.
            </p> <br>
            <p style="margin-left: 5rem;margin-right: 5rem; text-align: justify;">
                <strong>3. EQUIPAMIENTO TÉCNICO INCLUIDO </strong> <br><br>
                Para garantizar la eficiencia, cada puesto de seguridad cuenta con:

            </p>

            <p style="margin-left: 7rem;margin-right: 5rem; text-align: justify;">

                • Sistema de radiocomunicación (Handig)
                • Dispositivo móvil o Tablet para control de ingresos y rondas, todo mediante
                nuestro sistema digital tecnológico incluido en nuestro servicio.
                • Linternas de alta potencia para patrullaje nocturno.
                • Uniformes tácticos de alta visibilidad y equipo de protección personal (EPP)
                • Vehículo motocicleta y bicicletas en caso de ser necesario para perímetros
                extensos.
                • Cámaras según requerimiento ya sea fija o movible para el personal de
                seguridad.

            </p> <br>
            <p style="margin-left: 5rem;margin-right: 5rem; text-align: justify;">
                <strong>4. DETALLE DE LA INVERSIÓN</strong>
            </p>
            @php
                $i = 0;
            @endphp
            @if (count($detalles) > 0)
                <div style="margin-left: 40px; margin-right: 40px; margin-top: 20px; text-align: justify;">

                    <table style="width: 100%; border-collapse: collapse; font-size: 12px;">

                        <thead>
                            <tr style="background-color: #143c77; color: white;">
                                <th style="width: 8%; padding: 6px; border: 1px solid #ccc;">N°</th>
                                <th style="padding: 6px; border: 1px solid #ccc;">DETALLE</th>
                                <th style="width: 20%; padding: 6px; border: 1px solid #ccc; text-align: right;">PRECIO
                                    UNIT. MES</th>
                                <th style="width: 15%; padding: 6px; border: 1px solid #ccc;">CANTIDAD</th>
                                <th style="width: 15%; padding: 6px; border: 1px solid #ccc; text-align: right;">
                                    SUBTOTAL</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php $total = 0; @endphp

                            @foreach ($detalles as $detalle)
                                @php
                                    $subtotal = $detalle['cantidad'] * $detalle['precio'];
                                    $total += $subtotal;
                                @endphp

                                <tr style="background-color: {{ $loop->index % 2 == 0 ? '#f2f4f7' : '#ffffff' }};">
                                    <td style="padding: 5px; border: 1px solid #ddd; text-align: center;">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td style="padding: 5px; border: 1px solid #ddd;">
                                        {{ $detalle['detalle'] }}
                                    </td>

                                    <td style="padding: 5px; border: 1px solid #ddd; text-align: right;">
                                        {{ number_format($detalle['precio'], 2, '.') }}
                                    </td>

                                    <td style="padding: 5px; border: 1px solid #ddd; text-align: center;">
                                        {{ $detalle['cantidad'] }}
                                    </td>

                                    <td style="padding: 5px; border: 1px solid #ddd; text-align: right;">
                                        {{ number_format($subtotal, 2, '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr style="background-color: #e6ecf5; font-weight: bold;">
                                <td colspan="4" style="padding: 6px; border: 1px solid #ccc; text-align: right;">
                                    TOTAL INVERSIÓN MENSUAL
                                </td>
                                <td style="padding: 6px; border: 1px solid #ccc; text-align: right;">
                                    {{ number_format($total, 2, '.') }}
                                </td>
                            </tr>
                        </tfoot>

                    </table>
                    ___________________________________________________________________________________

                </div>
            @endif
            <br>
            <p style="margin-left: 5rem;margin-right: 5rem; text-align: justify;">
                <strong>5. CONDICIONES COMERCIALES Y GARANTÍAS </strong> <br><br>
                Para proteger a ambas partes y demostrar seriedad:
            </p>
            <p style="margin-left: 7rem;margin-right: 5rem; text-align: justify;">
                • Garantía de Reemplazo: En caso de ausencia o enfermedad,
                garantizamos el reemplazo del vigilante. <br>
                • Forma de Pago: Mediante cheque o Transferencia Bancaria. <br>
                • Seguro de Responsabilidad Civil y Accidentes personales: Rialto
                Patrol cuenta con póliza vigente para cubrir eventualidades operativas.
            </p>
            <p style="margin-left: 5rem;margin-right: 5rem; text-align: justify;">
                ________________________________________________________________________________ <br> <br>
                <strong>6. ACEPTACIÓN DE LA PROPUESTA </strong> <br><br>
                Para dar inicio al esquema de seguridad, favor de firmar al calce o enviar orden
                de compra referenciando esta cotización.

            </p>
            <p style="margin-left: 3rem;margin-right: 3rem; margin-top: 12rem;text-align: center;">
                @if ($encryptedId != '' && $datos1[1] > 0)
                <p style="margin-left: 3rem;margin-right: 3rem; margin-top: 2rem;text-align: center;">
                    <img src="{{ $qrUrl }}" alt="QR Code"><br>
                @else
                <p style="margin-left: 3rem;margin-right: 3rem; margin-top: 8rem;text-align: center;">
            @endif
                <br>
                <strong>
                    Ing. David Manzano Souza
                </strong>
                <br>
                Representante Legal <br>
                Tel.:68939554 <br>
                Correo: dmanzano@rialtopatrol.com
            </p>
        </section>
        <div style="page-break-after: always;"></div>
        <section>
            <div class="row" style="width: 100%;margin-right: 3rem;">
                <div class="col-xs-3 text-center">
                    <img class="img-responsive" src="{{ asset('images/logo_shield.png') }}" style="width: 90px;">
                    <h4>
                        {{ Str::upper(config('app.name')) }} <br>
                        <small style="font-size: 8.5px">SEGURIDAD PRIVADA Y VIGILANCIA</small>
                    </h4>



                </div>

                <div class="col-xs-3 text-right">

                </div>
                <div class="col-xs-4 text-center">

                </div>
            </div>

            <h4 style="text-align: center;"><strong><u>NUESTRA PROPUESTA INCLUYE</u></strong></h4><br>


            <p style="margin-left: 5rem;margin-right: 5rem; text-align: justify;">
                <strong>ANÁLISIS DE RIESGO DE LAS INSTALACIONES</strong> <br><br>
                Nuestros especialistas en seguridad realizarán un análisis de riesgos de las
                instalaciones, con la finalidad de determinar las vulnerabilidades y puntos críticos,
                el que se pondrá a su disposición para su evaluación y determinación.
                <br><br>
                <strong>PERSONAL CALIFICADO Y EVALUADO</strong> <br><br>
                El personal que brindará servicios especializados en Seguridad y Vigilancia se
                encuentra debidamente seleccionado, entrenado y evaluado de acuerdo a las
                disposiciones del DENACEV.

            </p>
            <br>
            <div class="row" style="margin-left: 5rem;margin-right: 5rem; text-align: justify;">
                <div class="col-xs-7">
                    • Programa de instrucción
                    • Evaluación completa anual. <br>
                    • Antecedentes (policiales, penales,
                    domiciliario, laborales) y referencias
                    comprobadas. <br>
                    • Remunerado adecuadamente. (Pagos
                    puntuales a través del sistema bancario). <br>
                    • Entrenado en técnicas de Seguridad. <br>
                    • Entrenado en uso del PR-24 (tolete). <br>
                    • Capacitación en Manejo de Extintores y
                    Primeros Auxilios. <br>
                    • Vestimentas (Camisa roja, Pantalón Plomo,
                    Pantalón y Camisa Jean Azul en área
                    industriales) con muy buen estado de
                    presentación. <br>
                    • Constante evaluación del desempeño del personal.
                </div>
                <div class="col-xs-5 text-right">
                    <img src="{{ asset('images/guardia.png') }}"
                        style="height:260px; margin-right: 5rem;margin-top:10px    ">
                </div>
            </div>
            <br>
            <p style="margin-left: 5rem;margin-right: 5rem; text-align: justify;">
                <strong>ADMINISTRACIÓN SALARIAL</strong> <br><br>
                Pago puntual de las remuneraciones de los trabajadores, mediante el sistema
                bancario. Beneficios sociales; aportaciones a Gestora y seguro de Caja Nacional
                de Salud, etc.

                <br><br>
                <strong>PÓLIZAS DE SEGURO</strong> <br><br>
                Nuestros clientes están respaldados por pólizas de seguros como Responsabilidad
                Civil por un monto de 250.000 Bs; como nuestros vigilantes con una póliza contra
                accidentes con cobertura de hasta 10.000$.
            </p>
        </section>
        <div style="page-break-after: always;"></div>
        <section>
            <div class="row" style="width: 100%;margin-right: 3rem;">
                <div class="col-xs-3 text-center">
                    <img class="img-responsive" src="{{ asset('images/logo_shield.png') }}" style="width: 90px;">
                    <h4>
                        {{ Str::upper(config('app.name')) }} <br>
                        <small style="font-size: 8.5px">SEGURIDAD PRIVADA Y VIGILANCIA</small>
                    </h4>



                </div>

                <div class="col-xs-3 text-right">

                </div>
                <div class="col-xs-4 text-center">

                </div>
            </div>

            <h4 style="text-align: center;"><strong><u>DATOS COMERCIALES: </u></strong></h4><br>


            <p style="margin-left: 10rem;margin-right: 14rem; text-align: justify;">
                Denominación / Razón Social: <strong>RIALTO PATROL SEGURIDAD Y
                    VIGILANCIA SRL.</strong> <br><br>
            </p>
            <table border="0"
                style="border-collapse: separate; margin-left: 8rem;margin-right: 12rem;
                text-align: justify; padding: 10px; border-collapse: separate;border-spacing: 10px;">
                <tbody>
                    <tr>
                        <td style="vertical-align: top;">
                            <strong>NIT:</strong>
                        </td>
                        <td style="vertical-align: top;">
                            393267024
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;">
                            <strong>Representante Legal:</strong>
                        </td>
                        <td style="vertical-align: top;">
                            Ing. David Manzano Souza
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;">
                            <strong>Dirección Fiscal:</strong>
                        </td>
                        <td style="vertical-align: top;">
                            Av. Cumavi Nº 4305, Barrio San Juan
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;">
                            <strong>Correo Electrónico:</strong>
                        </td>
                        <td style="vertical-align: top;">
                            cotizaciones@rialtopatrol.com<br> info@rialtopatrol.com <br> gerencia@rialtopatrol.com;

                        </td>
                    </tr>

                </tbody>

            </table>

            <br><br>
            <h4 style="text-align: center;"><strong><u>AUTORIZACIONES DE FUNCIONAMIENTO </u></strong></h4><br>
            <p style="margin-left: 10rem;margin-right: 14rem; text-align: justify;">

                • Resolución Ministerial N° 160/2025 en la modalidad de Seguridad y
                Vigilancia Privada. <br>
                • Ministerio de Trabajo-Certificado de Registro Obligatorio de Empleadores -
                ROE- Código Empleador 393267024-1 <br>
                • LICENCIA DE FUNCIONAMIENTO DENACEV No. BOL-1170 <br>
                • Número de Identificación Tributaria- NIT 393267024 <br>
                • Licencia de Funcionamiento de Actividades Económicas N.º 268603 <br>
                • SEPREC – Registro de Comercio con Matrícula N.º 393267024 <br>
                • GESTORA- Certificado de registro. <br>
                • CNS- Registro Patronal de la Caja Nacional de Salud <br>
                • POLIZA- Responsabilidad Civil compañía de seguro ASEGURADORA
                FORTALEZA <br>
                • POLIZAS- Accidentes personales compañía de seguro ASEGURADORA
                FORTALEZA

            </p>

        </section>
        <div style="page-break-after: always;"></div>
        <section>
            <div class="row" style="width: 100%;margin-right: 3rem;">
                <div class="col-xs-3 text-center">
                    <img class="img-responsive" src="{{ asset('images/logo_shield.png') }}" style="width: 90px;">
                    <h4>
                        {{ Str::upper(config('app.name')) }} <br>
                        <small style="font-size: 8.5px">SEGURIDAD PRIVADA Y VIGILANCIA</small>
                    </h4>



                </div>

                <div class="col-xs-3 text-right">

                </div>
                <div class="col-xs-4 text-center">

                </div>
            </div>

            <h4 style="text-align: center;"><strong><u>VENTAJAS QUE OFRECEMOS</u></strong></h4><br>
            <p style="margin-left: 5rem;margin-right: 5rem; text-align: justify;">
                <strong>SUPERVISIÓN EFECTIVA Y CONTROL PERMANENTE DEL SERVICIO DE
                    SEGURIDAD Y VIGILANCIA:</strong>
            </p>
            <p style="margin-left: 7rem;margin-right: 5rem; text-align: justify;">
                • Centro de Control y Comunicaciones, alerta las 24 horas. <br>
                • Sistema de Supervisión efectiva en forma programada. <br>
                • Apoyo de nuestras Unidades móviles. <br>
                • Control de unidades por GPS. (rondas motorizadas) <br>
                • Corporativos grupos de WhatsApp <br>
                • Control de rondas mediante georeferencia- aplicación propia de RIALTO
                PATROL
            </p>
            <br>
            <div class="row" style="margin-left: 5rem;margin-right: 10rem; text-align: justify;">
                <div class="col-xs-4">
                    <img src="{{ asset('images/guardia1.jpg') }}"
                        style="height:220px; margin-right: 5rem;margin-top:10px    ">
                </div>
                <div class="col-xs-8">
                    • Programa de instrucción
                    • Evaluación completa anual. <br>
                    • Antecedentes (policiales, penales,
                    domiciliario, laborales) y referencias
                    comprobadas. <br>
                    • Remunerado adecuadamente. (Pagos
                    puntuales a través del sistema bancario). <br>
                    • Entrenado en técnicas de Seguridad. <br>
                    • Entrenado en uso del PR-24 (tolete). <br>
                    • Capacitación en Manejo de Extintores y
                    Primeros Auxilios. <br>
                    • Vestimentas (Camisa roja, Pantalón Plomo,
                    Pantalón y Camisa Jean Azul en área
                    industriales) con muy buen estado de
                    presentación. <br>
                    • Constante evaluación del desempeño del personal.
                </div>
            </div>
            <br>
            <div class="row" style="margin-left: 5rem;margin-right: 10rem; text-align: justify;">

                <div class="col-xs-6">
                    <img src="{{ asset('images/guardia2.jpg') }}"
                        style="height:220px; margin-right: 5rem;margin-top:10px    ">
                </div>
                <div class="col-xs-6">
                    <img src="{{ asset('images/guardia3.jpg') }}"
                        style="height:220px; margin-right: 5rem;margin-top:10px    ">
                </div>
            </div>
        </section>
        <div style="page-break-after: always;"></div>
        <section>
            <div class="row" style="width: 100%;margin-right: 3rem;">
                <div class="col-xs-3 text-center">
                    <img class="img-responsive" src="{{ asset('images/logo_shield.png') }}" style="width: 90px;">
                    <h4>
                        {{ Str::upper(config('app.name')) }} <br>
                        <small style="font-size: 8.5px">SEGURIDAD PRIVADA Y VIGILANCIA</small>
                    </h4>



                </div>

                <div class="col-xs-3 text-right">

                </div>
                <div class="col-xs-4 text-center">

                </div>
            </div>
            <p style="margin-left: 5rem;margin-right: 5rem; text-align: justify;">
                <strong> DESEMPEÑO EN SUS FUNCIONES:</strong>
            </p>
            <p style="margin-left: 7rem;margin-right: 5rem; text-align: justify;">
                • De calidad y en óptimos resultados. <br>
                • Capacitaciones constantes en Seguridad.
            </p>
            <br>
            <p style="margin-left: 5rem;margin-right: 5rem; text-align: justify;">
                <strong>GARANTÍA DE NUESTRA ORGANIZACIÓN: </strong>
            </p>
            <p style="margin-left: 5rem;margin-right: 5rem; text-align: justify;">
                Somos una empresa con experiencia dedicada a prestar servicios de seguridad y
                Vigilancia a importantes empresas e instituciones, atendiendo a una selecta cartera
                de clientes a los cuales prestamos un servicio profesional y eficiente aplicando los
                más altos estándares modernos sobre Seguridad Integral.
                Contamos con los recursos humanos, técnicos, financieros y la infraestructura
                necesaria para garantizar un servicio de acuerdo a sus necesidades.


            </p>

            <div class="row" style="margin-left: 8rem;margin-right: 12rem; text-align: justify;">
                <div class="col-xs-4">
                    <img src="{{ asset('images/guardia4.jpg') }}"
                        style="height:220px; margin-right: 5rem;margin-top:10px    ">
                </div>
                <div class="col-xs-8">
                    <br><br>
                    Mantenemos preparados y
                    entrenados en uso de equipos para
                    el control de Alcoholemia al
                    personal que ingresan a las
                    instalaciones.
                    Capacitación en llenado de libros
                    de control y novedades.

                    Monitoreo diario de los relevos y
                    servicio las 24 horas
                </div>
            </div>
            <br>
            <div class="row" style="margin-left: 8rem;margin-right: 12rem; text-align: justify;">

                <div class="col-xs-6">
                    <img src="{{ asset('images/guardia5.jpg') }}"
                        style="height:220px; margin-right: 5rem;margin-top:10px    ">
                </div>
                <div class="col-xs-6">
                    <img src="{{ asset('images/guardia6.jpg') }}"
                        style="height:220px; margin-right: 5rem;margin-top:10px    ">
                </div>
            </div>
        </section>
        <div style="page-break-after: always;"></div>
        <section>
            <div class="row" style="width: 100%;margin-right: 3rem;">
                <div class="col-xs-3 text-center">
                    <img class="img-responsive" src="{{ asset('images/logo_shield.png') }}" style="width: 90px;">
                    <h4>
                        {{ Str::upper(config('app.name')) }} <br>
                        <small style="font-size: 8.5px">SEGURIDAD PRIVADA Y VIGILANCIA</small>
                    </h4>



                </div>

                <div class="col-xs-3 text-right">

                </div>
                <div class="col-xs-4 text-center">

                </div>
            </div>
            <h3 style="text-align: center;margin-top: 10rem; color: #686767;">RIALTO PATROL SEGURIDAD Y VIGILANCIA SRL </h3>

            <p style="margin-left: 13rem;margin-right: 13rem; margin-top: 10rem; text-align: center; font-size: 17px;color: #686767;">
                “En RIALTO PATROL nos sentimos comprometidos con el
                crecimiento de nuestros clientes, y buscamos ofrecer la mejor
                solución del mercado generando eficiencia en cada servicio de
                seguridad que iniciamos.”
            </p>

            <h3 style="text-align: center;margin-top: 10rem; color: #686767;">BIENVENIDO A UNA NUEVA EXPERIENCIA</h3>
            <p style="margin-left: 3rem;margin-right: 3rem; margin-top: 8rem;text-align: center;">
                <img src="{{ asset('images/firma.png') }}" alt="Firma" style="height: 100px;"><br>

                <strong>
                    Ing. David Manzano Souza
                </strong>
                <br> REPRESENTANTE LEGAL <br> RIALTO PATROL SRL
            </p>

        </section>
    </div>

    <script src="{{ asset('vendor/bs3/bootstrap.min.js') }}"></script>
</body>

</html>
