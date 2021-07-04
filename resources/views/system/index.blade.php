@extends('layouts.basic')

@section('title', 'Sistema')

@section('content')
    <section>
        <strong>Sistema</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <nav>
                Atribuições
                <ul>
                    <li><a href="{{ route('role.index') }}">Listar Atribuições</a></li>
                    <li><a href="{{ route('role.create') }}">Cadastrar Atribuição</a></li>
                </ul><br />
                Cursos
                <ul>
                    <li><a href="{{ route('course.index') }}">Listar Cursos</a></li>
                    <li><a href="{{ route('course.create') }}">Cadastrar Curso</a></li>
                </ul><br />
                Usuários
                <ul>
                    <li><a href="{{ route('user.index') }}">Listar Usuários</a></li>
                    <li><a href="{{ route('user.create') }}">Cadastrar Usuário</a></li>
                </ul>
            </nav>
            <br /><br />
        </main>
    </section>
@endsection
