<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>Alavora</title>

    <script src="{{ asset('js/app.js') }}" defer></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/welcome.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="example col-md-6 text-center mx-auto">
                <img src="{{ asset('/images/logo.png') }}" />
            </div>
        </div>
        <div class="row">
            <div class="example col-md-6 text-center mx-auto">
                <p>
                    Benvinguts a Alavora, l'aplicació per comprar <em>a distància el que tens a prop</em>.
                </p>
                <p>
                    L'aplicació està diferenciada per segons quin usauri siguis, un comprador o un venedor.
                </p>
                <a href='https://clients.alavora.cat'>
                    <button type="button" class="btn btn-alavora">Client</button>
                </a>
                <a href='https://sellers.alavora.cat'>
                    <button type="button" class="btn btn-alavora">Venedor</button>
                </a>
            </div>
        </div>
    </div>

</body>

</html>
