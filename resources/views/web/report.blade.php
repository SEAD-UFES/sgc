@extends('layouts.basic')

@section('title', 'Relatórios')

@section('content')
    <section>
        <strong>Relatórios</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <nav>
                <h3>Termos e Licença</h3>
                <ul>
                    <li><a href="{{ route('bonds.rights.index') }}">Listar Documentos de Termos e Licença</a></li>
                    {{-- <li><a href="{{ route('approveds.create') }}">Importar Aprovados</a></li> --}}
                    {{-- <li><a href="{{ route('approveds.create') }}">Cadastrar Aprovado</a></li> --}}
                </ul><br />
                @canany(['isAdm', 'isDir', 'isAss', 'isSec', 'isGra'])
                    <h3 style="background-color: rosybrown;text-decoration:line-through">Alocação de colaboradores</h3>
                    <ul>
                        <li><a href="#">!Relatório de alocações!</a></li>
                        {{-- <li><a href="{{ route('employees.create') }}">Cadastrar Colaborador</a></li>
                    <li><a href="{{ route('employees.document.index') }}">Listar Documento de Colaboradores</a></li>
                    <li><a href="{{ route('employees.document.create') }}">Importar Documento de Colaborador</a></li> --}}
                    </ul><br />
                @endcanany
            </nav>
            <br /><br />
        </main>
    </section>
@endsection
