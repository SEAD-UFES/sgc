@extends('layouts.basic')

@section('title', 'Colaboradores')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item active" aria-current="page">Colaboradores</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <h3>Aprovados</h3>
            <nav class="navbar navbar-light">
                <div class="container-fluid">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" href="{{ route('approveds.index') }}">Listar
                                Aprovados</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('approveds.create') }}">Importar
                                Aprovados</a></li>
                        {{-- <li><a href="{{ route('approveds.create') }}">Cadastrar Aprovado</a></li> --}}
                    </ul>
                </div>
            </nav>
            <h3>Colaboradores</h3>
            <nav class="navbar navbar-light">
                <div class="container-fluid">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" href="{{ route('employees.index') }}">Listar
                                Colaboradores</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('employees.create') }}">Cadastrar
                                Colaborador</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('employees.document.index') }}">Listar
                                Documentos de Colaboradores</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('employees.document.create') }}">Importar
                                Documentos de Colaborador</a></li>
                    </ul>
                </div>
            </nav>
            <h3>Vínculos</h3>
            <nav class="navbar navbar-light">
                <div class="container-fluid">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" href="{{ route('bonds.index') }}">Listar Vínculos</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('bonds.create') }}">Cadastrar Vínculo</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('bonds.document.index') }}">Listar
                                Documento de Vínculos</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('bonds.document.create') }}">Importar
                                Documento de Vínculo</a></li>
                    </ul>
                </div>
            </nav>
            {{-- <h3 style="background-color: rosybrown;text-decoration:line-through">Alocação</h3>
            <ul>
                <li><a href="#">!Listar Alocações!</a></li>
                <li><a href="#">!Alocar tutores a distância!</a></li>
            </ul> --}}
        </main>
    </section>
@endsection
