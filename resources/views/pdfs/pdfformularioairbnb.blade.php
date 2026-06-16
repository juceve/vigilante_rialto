<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Form-Airbnb</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/bs3/bootstrap.min.css') }}">
    
    <style>
       body {
    font-family: 'Helvetica', sans-serif; /* Sustituye 'Arial' con la fuente que desees */
}
    </style>
</head>

<body>



    <br>
    <p class="text-center">
        <span style="font-size: 16px"><strong>FORMULARIO AIRBNB</strong></span> <br>
        <span style="font-size: 14px"><strong>{{ $condominio }}</strong></span> <br>
        <span>Nro. Reg.: {{ str_pad($id, 6, '0', STR_PAD_LEFT) }}</span>
    </p>
    <h4 class="text-center"></h4>

    <table class="table table-bordered table-striped table-condensed" style="vertical-align: middle; font-size: 12px">

        <tbody>
            <tr style="background-color: slategrey;color: white">
                <td style="width: 30%">
                    <label>
                        <strong>
                            TITULAR:
                        </strong>
                    </label>
                </td>
                <td>
                    {{ $name }}
                </td>
            </tr>
            <tr>
                <td style="width: 30%">
                    <label>
                        <strong>
                            Info Departamento:
                        </strong>
                    </label>
                </td>
                <td>
                    {{ $department_info }}
                </td>
            </tr>
            <tr>
                <td style="width: 30%">
                    <label>
                        <strong>
                            Fecha-Hora llegada:
                        </strong>
                    </label>
                </td>
                <td>
                    {{ $arrival_date }}
                </td>
            </tr>
            <tr>
                <td style="width: 30%">
                    <label>
                        <strong>
                            Fecha-Hora salida:
                        </strong>
                    </label>
                </td>
                <td>
                    {{ $departure_date }}
                </td>
            </tr>
            <tr>
                <td style="width: 30%">
                    <label>
                        <strong>
                            Fecha nacimiento:
                        </strong>
                    </label>
                </td>
                <td>
                    {{ $birth_date }}
                </td>
            </tr>
            <tr>
                <td style="width: 30%">
                    <label>
                        <strong>
                            Tipo Doc.:
                        </strong>
                    </label>
                </td>
                <td>
                    {{ $document_type }}
                </td>
            </tr>
            <tr>
                <td style="width: 30%">
                    <label>
                        <strong>
                            Nro. Doc.:
                        </strong>
                    </label>
                </td>
                <td>
                    {{ $document_number }}
                </td>
            </tr>
            <tr>
                <td style="width: 30%">
                    <label>
                        <strong>
                            Ciudad de Origen:
                        </strong>
                    </label>
                </td>
                <td>
                    {{ $city_of_origin }}
                </td>
            </tr>
            <tr>
                <td style="width: 30%">
                    <label>
                        <strong>
                            Estado civil:
                        </strong>
                    </label>
                </td>
                <td>
                    {{ $marital_status }}
                </td>
            </tr>
            <tr>
                <td style="width: 30%">
                    <label>
                        <strong>
                            Dirección:
                        </strong>
                    </label>
                </td>
                <td>
                    {{ $address }}
                </td>
            </tr>
            <tr>
                <td style="width: 30%">
                    <label>
                        <strong>
                            Ciudad:
                        </strong>
                    </label>
                </td>
                <td>
                    {{ $city }}
                </td>
            </tr>
            <tr>
                <td style="width: 30%">
                    <label>
                        <strong>
                            Pais:
                        </strong>
                    </label>
                </td>
                <td>
                    {{ $country }}
                </td>
            </tr>
            <tr>
                <td style="width: 30%">
                    <label>
                        <strong>
                            Correo electronico:
                        </strong>
                    </label>
                </td>
                <td>
                    {{ $email }}
                </td>
            </tr>
            <tr>
                <td style="width: 30%">
                    <label>
                        <strong>
                            Telefono:
                        </strong>
                    </label>
                </td>
                <td>
                    {{ $phone }}
                </td>
            </tr>
            <tr>
                <td style="width: 30%">
                    <label>
                        <strong>
                            Ocupación:
                        </strong>
                    </label>
                </td>
                <td>
                    {{ $occupation }}
                </td>
            </tr>
            <tr>
                <td style="width: 30%">
                    <label>
                        <strong>
                            Cant. Equipaje:
                        </strong>
                    </label>
                </td>
                <td>
                    {{ $luggage_count }}
                </td>
            </tr>
            <tr>
                <td style="width: 30%">
                    <label>
                        <strong>
                            Empmresa:
                        </strong>
                    </label>
                </td>
                <td>
                    {{ $company }}
                </td>
            </tr>
            <tr>
                <td style="width: 30%">
                    <label>
                        <strong>
                            Motivo del Viaje:
                        </strong>
                    </label>
                </td>
                <td>
                    {{ $travel_purpose }}
                </td>
            </tr>
            <tr>
                <td style="width: 30%">
                    <label>
                        <strong>
                            ESTADO DEL REGISTRO:
                        </strong>
                    </label>
                </td>
                <td>
                    {{ $status }}
                </td>
            </tr>
        </tbody>
    </table>
    @if ($companions_count > 0)
        <hr>
        <h5 class="text-center">ACOMPAÑANTES</h5>
        <table class="table table-bordered table-striped table-condensed"
            style="vertical-align: middle; font-size: 12px">
            @php
                $c = 1;
            @endphp
            @foreach ($companions_data as $companion)
                <tr style="background-color: slategrey;color: white">
                    <td style="width: 30%">ACOMPAÑANTE {{ $c++ }}</td>
                    <td>{{ $companion['name'] }}</td>
                </tr>
                <tr>
                    <td style="width: 30%">
                        <label><strong>Fecha nacimiento:</strong></label>
                    </td>
                    <td>
                        {{ $companion['birth_date'] }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%">
                        <label><strong>Tipo Doc.:</strong></label>
                    </td>
                    <td>
                        {{ $companion['document_type'] }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%">
                        <label><strong>Nro. Doc.:</strong></label>
                    </td>
                    <td>
                        {{ $companion['document_number'] }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%">
                        <label><strong>Nacionalidad:</strong></label>
                    </td>
                    <td>
                        {{ $companion['nationality'] }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%">
                        <label><strong>Cant. Equipaje:</strong></label>
                    </td>
                    <td>
                        {{ $companion['luggage_count'] }}
                    </td>
                </tr>
            @endforeach

        </table>
    @endif

    <script src="{{ asset('vendor/bs3/bootstrap.min.js') }}"></script>
</body>

</html>
