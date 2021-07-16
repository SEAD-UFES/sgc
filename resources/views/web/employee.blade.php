@extends('layouts.basic')

@section('title', 'Colaboradores')

@section('content')
    <section>
        <strong>Colaboradores</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <nav>
                <h3>Aprovados</h3>
                <ul>
                    <li><a href="{{ route('approveds.index') }}">Listar Aprovados</a></li>
                    <li><a href="{{ route('approveds.create') }}">Importar Aprovados</a></li>
                    {{-- <li><a href="{{ route('approveds.create') }}">Cadastrar Aprovado</a></li> --}}
                </ul><br />
                <h3>Colaboradores</h3>
                <ul>
                    <li><a href="{{ route('employees.index') }}">Listar Colaboradores</a></li>
                    <li><a href="{{ route('employees.create') }}">Cadastrar Colaborador</a></li>
                    <li><a href="{{ route('employees.document.index') }}">Listar Documento de Colaboradores</a></li>
                    <li><a href="{{ route('employees.document.create') }}">Importar Documento de Colaborador</a></li>
                </ul><br />
                {{-- <h3>Documentos</h3>
                <ul>
                    <li><a href="{{ route('documents.index') }}">Listar Documentos</a></li>
                    <li><a href="{{ route('documents.create') }}">Importar Documentos</a></li>
                </ul><br /> --}}
                <h3>Vínculos</h3>
                <ul>
                    <li><a href="{{ route('bonds.index') }}">Listar Vínculos</a></li>
                    <li><a href="{{ route('bonds.create') }}">Cadastrar Vínculo</a></li>
                    <li><a href="{{ route('bonds.document.index') }}">Listar Documento de Vínculos</a></li>
                    <li><a href="{{ route('bonds.document.create') }}">Importar Documento de Vínculo</a></li>
                </ul><br />
            </nav>
            <br /><br />
        </main>
    </section>
@endsection
