<!doctype html>
<html lang="pt-BR" class="no-js">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="canonical" href="https://html5-templates.com/" />
    <title>SGC - @yield('title')</title>
    <meta name="description" content="SGC - Sistema de Gestão de Colaboradores da Sead/Ufes">
    <link rel="shortcut icon" href="{{ asset('/sead.png') }}">
    <link rel="stylesheet" href="{{ asset('/style.css') }}">
    <script src="/script.js"></script>
</head>

<body>
    @include('layouts.parcialHeader')
    @yield('content')
    @include('layouts.parcialFooter')
</body>

</html>