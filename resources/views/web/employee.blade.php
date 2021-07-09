@extends('layouts.basic')

@section('title', 'Colaboradores')

@section('content')
    <section>
        <strong>Colaboradores</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <nav>
                Colaboradores
                <ul>
                    <li><a href="{{ route('employees.index') }}">Listar Colaboradores</a></li>
                    <li><a href="{{ route('employees.create') }}">Cadastrar Colaboradores</a></li>
                    {{-- <li><a href="{{ route('employees.index') }}">Listar Tipos de Colaboradores</a></li> --}}
                </ul><br />
                </ul><br />
                {{-- Cursos
                <ul>
                    <li><a href="{{ route('courses.index') }}">Listar Cursos</a></li>
                    <li><a href="{{ route('courses.create') }}">Cadastrar Curso</a></li>
                    <li><a href="{{ route('coursetypes.index') }}">Listar Tipos de Cursos</a></li>
                </ul><br />
                Usuários
                <ul>
                    <li><a href="{{ route('users.index') }}">Listar Usuários</a></li>
                    <li><a href="{{ route('users.create') }}">Cadastrar Usuário</a></li>
                </ul> --}}
            </nav>
            <br /><br />
        </main>
    </section>
@endsection
