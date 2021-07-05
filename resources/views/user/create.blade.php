@extends('layouts.basic')

@section('title', 'Cadastrar Usuário')

@section('content')
    <section>
        <strong>Cadastrar Usuário</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <form action={{ route('user.store') }} method="POST">
                @component('user.componentUserForm', ['roles' => $roles])@endcomponent
            </form>
        </main>
    </section>
@endsection
