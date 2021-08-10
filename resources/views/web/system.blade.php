@extends('layouts.basic')

@section('title', 'Sistema')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item active" aria-current="page">Sistema</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <h3>Atribuições</h3>
            <nav class="navbar navbar-light">
                <div class="container-fluid">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" href="{{ route('roles.index') }}">Listar Atribuições</a>
                        </li>
                        @canany(['isAdm', 'isDir', 'isAss', 'isSec'])
                            <li class="nav-item"><a class="nav-link" href="{{ route('roles.create') }}">Cadastrar
                                    Atribuição</a></li>
                        @endcanany
                    </ul>
                </div>
            </nav>
            <h3>Cursos</h3>
            <nav class="navbar navbar-light">
                <div class="container-fluid">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" href="{{ route('courses.index') }}">Listar Cursos</a>
                        </li>
                        @canany(['isAdm', 'isDir', 'isAss', 'isSec'])
                            <li class="nav-item"><a class="nav-link" href="{{ route('courses.create') }}">Cadastrar Curso</a>
                            </li>
                        @endcanany
                        <li class="nav-item"><a class="nav-link" href="{{ route('coursetypes.index') }}">Listar Tipos de
                                Cursos</a></li>
                    </ul>
                </div>
            </nav>
            <h3>Polos</h3>
            <nav class="navbar navbar-light">
                <div class="container-fluid">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" href="{{ route('poles.index') }}">Listar Polos</a></li>
                        @canany(['isAdm', 'isDir', 'isAss', 'isSec'])
                            <li class="nav-item"><a class="nav-link" href="{{ route('poles.create') }}">Cadastrar Polo</a>
                            </li>
                        @endcanany
                    </ul>
                </div>
            </nav>
            @canany(['isAdm'])
                <h3>Usuários</h3>
                <nav class="navbar navbar-light">
                    <div class="container-fluid">
                        <ul class="navbar-nav">
                            <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}">Listar Usuários</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('users.create') }}">Cadastrar Usuário</a>
                            </li>
                        </ul>
                    @endcanany
                </div>
            </nav>
        </main>
    </section>
@endsection
