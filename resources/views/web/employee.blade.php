@extends('layouts.basic')

@section('title', 'Colaboradores')

@section('content')
    <section>
        <strong>Colaboradores</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <nav>
                <h3>Colaboradores</h3>
                <ul>
                    <li><a href="{{ route('employees.index') }}">Listar Colaboradores</a></li>
                    <li><a href="{{ route('employees.create') }}">Cadastrar Colaborador</a></li>
                </ul><br />
                <h3>Vínculos</h3>
                <ul>
                    <li><a href="{{ route('bonds.index') }}">Listar Vínculos</a></li>
                    <li><a href="{{ route('bonds.create') }}">Cadastrar Vínculo</a></li>
                </ul><br />
            </nav>
            <br /><br />
        </main>
    </section>
@endsection
