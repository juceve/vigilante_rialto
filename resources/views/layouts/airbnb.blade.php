<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Página de aviso de registro expirado para Airbnb">
    <title>REGISTRO DE VISITAS AIRBNB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to bottom, #74ebd5, #9face6);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            font-family: 'Arial', sans-serif;
            color: #343a40;
            padding-top: 2rem;
        }

        .card {
            max-width: 500px;
            border: none;
            border-radius: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .card-header {
            background-color: #0077b6;
            color: #fff;
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
        }
    </style>
    @livewireStyles
</head>

<body>
    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts
</body>

</html>
