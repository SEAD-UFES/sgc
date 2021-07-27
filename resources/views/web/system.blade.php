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
                    @canany(['isAdm', 'isDir', 'isAss', 'isSec'])
                        <li><a href="{{ route('roles.create') }}">Cadastrar Atribuição</a></li>
                    @endcanany
                </ul><br />
                <h3>Cursos</h3>
                <ul>
                    <li><a href="{{ route('courses.index') }}">Listar Cursos</a></li>
                    @canany(['isAdm', 'isDir', 'isAss', 'isSec'])
                        <li><a href="{{ route('courses.create') }}">Cadastrar Curso</a></li>
                    @endcanany
                    <li><a href="{{ route('coursetypes.index') }}">Listar Tipos de Cursos</a></li>
                </ul><br />
                <h3>Polos</h3>
                <ul>
                    <li><a href="{{ route('poles.index') }}">Listar Polos</a></li>
                    @canany(['isAdm', 'isDir', 'isAss', 'isSec'])
                        <li><a href="{{ route('poles.create') }}">Cadastrar Polo</a></li>
                    @endcanany
                </ul><br />
                @canany(['isAdm'])
                    <h3>Usuários</h3>
                    <ul>
                        <li><a href="{{ route('users.index') }}">Listar Usuários</a></li>
                        <li><a href="{{ route('users.create') }}">Cadastrar Usuário</a></li>
                    </ul>
                @endcanany
            </nav>
            <br /><br />
        </main>
    </section>
@endsection
