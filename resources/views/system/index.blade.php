@extends('layouts.basic')

@section('title', 'Sistema')

@section('content')
    <section>
        <strong>Sistema</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <p>Sistema</p><br /><br />
            <a href="{{ route('role.index') }}">Atribuições</a><br /><br />
            <a href="{{ route('course.index') }}">Cursos</a><br /><br />
            <a href="{{ route('user.index') }}">Usuários</a><br /><br />
        </main>
    </section>
@endsection
