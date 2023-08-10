<!doctype html>
<html lang="pt-BR" class="no-js">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>SGC - @yield('title')</title>
    <meta name="description" content="SGC - Sistema de Gestão de Colaboradores da Sead/Ufes">
    <link rel="shortcut icon" href="{{ asset('/sead.png') }}">
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    
    @yield('headerScripts')
</head>

<body>
    @include('layouts.partialHeader')
    <div class="container-fluid">
        @yield('content')
    </div>
    @include('layouts.partialFooter')
    @yield('scripts')
    @vite('resources/js/enable_tooltip.ts')
</body>

</html>
