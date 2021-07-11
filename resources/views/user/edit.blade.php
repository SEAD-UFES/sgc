@extends('layouts.basic')

@section('title', 'Editar Usuário')

@section('content')
    <section>
        <strong>Editar Usuário</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @method('PATCH')
                @component('user.componentUserForm',  compact('user', 'userTypes'))@endcomponent
                <button type="submit">Atualizar</button> <button type="button" onclick="history.back()">Cancelar</button>
                @error('noStore')
                    <div class="error">> {{ $message }}</div>
                @enderror
                <br /><br />
            </form>
        </main>
    </section>
@endsection
