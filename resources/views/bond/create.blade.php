@extends('layouts.basic')

@section('title', 'Cadastrar Usuário')

@section('content')
    <section>
        <strong>Cadastrar Usuário</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <form action={{ route('users.store') }} method="POST">
                @component('user.componentUserForm',  compact('user', 'userTypes'))@endcomponent
            </form>
        </main>
    </section>
@endsection
