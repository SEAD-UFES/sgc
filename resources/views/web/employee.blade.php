@extends('layouts.basic')

@section('title', 'Colaboradores')

@section('content')
    <section>
        <h2>Colaboradores</h2>
    </section>
    <section id="pageContent">
        <main role="main">
            <nav>
                <h3>Aprovados</h3>
                <ul>
                    <li><a href="{{ route('approveds.index') }}">Listar Aprovados</a></li>
                    <li><a href="{{ route('approveds.create') }}">Importar Aprovados</a></li>
                    {{-- <li><a href="{{ route('approveds.create') }}">Cadastrar Aprovado</a></li> --}}
                </ul>
                <h3>Colaboradores</h3>
                <ul>
                    <li><a href="{{ route('employees.index') }}">Listar Colaboradores</a></li>
                    <li><a href="{{ route('employees.create') }}">Cadastrar Colaborador</a></li>
                    <li><a href="{{ route('employees.document.index') }}">Listar Documento de Colaboradores</a></li>
                    <li><a href="{{ route('employees.document.create') }}">Importar Documento de Colaborador</a></li>
                </ul>
                <h3>Vínculos</h3>
                <ul>
                    <li><a href="{{ route('bonds.index') }}">Listar Vínculos</a></li>
                    <li><a href="{{ route('bonds.create') }}">Cadastrar Vínculo</a></li>
                    <li><a href="{{ route('bonds.document.index') }}">Listar Documento de Vínculos</a></li>
                    <li><a href="{{ route('bonds.document.create') }}">Importar Documento de Vínculo</a></li>
                </ul>
                <h3 style="background-color: rosybrown;text-decoration:line-through">Alocação</h3>
                <ul>
                    <li><a href="#">!Listar Alocações!</a></li>
                    <li><a href="#">!Alocar tutores a distância!</a></li>
                </ul>
            </nav>
        </main>
    </section>
@endsection
