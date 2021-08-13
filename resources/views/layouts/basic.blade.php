<!doctype html>
<html lang="pt-BR" class="no-js">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>SGC - @yield('title')</title>
    <meta name="description" content="SGC - Sistema de Gestão de Colaboradores da Sead/Ufes">
    <link rel="shortcut icon" href="{{ asset('/sead.png') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}"></script>
</head>

<body>
    @include('layouts.parcialHeader')
    <div class="container-fluid">
        @yield('content')
    </div>
    @include('layouts.parcialFooter')
    @yield('scripts')
</body>

</html>
