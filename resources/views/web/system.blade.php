@extends('layouts.basic')

@section('title', 'Sistema')

@section('content')
    <section>
        <strong>Sistema</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <nav>
                <h3>Atribuições</h3>
                <ul>
                    <li><a href="{{ route('roles.index') }}">Listar Atribuições</a></li>
                    <li><a href="{{ route('roles.create') }}">Cadastrar Atribuição</a></li>
                </ul><br />
                <h3>Cursos</h3>
                <ul>
                    <li><a href="{{ route('courses.index') }}">Listar Cursos</a></li>
                    <li><a href="{{ route('courses.create') }}">Cadastrar Curso</a></li>
                    <li><a href="{{ route('coursetypes.index') }}">Listar Tipos de Cursos</a></li>
                </ul><br />
                <h3>Polos</h3>
                <ul>
                    <li><a href="{{ route('poles.index') }}">Listar Polos</a></li>
                    <li><a href="{{ route('poles.create') }}">Cadastrar Polo</a></li>
                </ul><br />
                <h3>Usuários</h3>
                <ul><br />
                    <li><a href="{{ route('users.index') }}">Listar Usuários</a></li>
                    <li><a href="{{ route('users.create') }}">Cadastrar Usuário</a></li>
                </ul>
            </nav>
            <br /><br />
        </main>
    </section>
@endsection