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
                <button type="submit">Cadastrar</button> <button type="button" onclick="history.back()">Cancelar</button>
                @error('noStore')
                    <div class="error">> {{ $message }}</div>
                @enderror
                <br /><br />
            </form>
        </main>
    </section>
@endsection
