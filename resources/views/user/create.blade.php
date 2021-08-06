@extends('layouts.basic')

@section('title', 'Cadastrar Usuário')

@section('content')
    <section>
        <h2>Cadastrar Usuário</h2>
    </section>
    <section id="pageContent">
        <main role="main">
            <form action={{ route('users.store') }} method="POST">
                @component('user.componentUserForm',  compact('user', 'userTypes'))@endcomponent
                <button type="submit" class="btn btn-primary">Cadastrar</button> <button type="button" onclick="history.back()" class="btn btn-secondary">Cancelar</button>
                @error('noStore')
                    <div class="error">> {{ $message }}</div>
                @enderror
            </form>
        </main>
    </section>
@endsection
