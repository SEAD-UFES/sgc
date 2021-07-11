@extends('layouts.basic')

@section('title', 'Cadastrar Atribuição')

@section('content')
    <section>
        <strong>Cadastrar Atribuição</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <form action={{ route('roles.store') }} method="POST">
                @component('role.componentRoleForm',  compact('role', 'grantTypes'))@endcomponent
                <button type="submit">Cadastrar</button> <button type="button" onclick="history.back()">Cancelar</button>
                @error('noStore')
                    <div class="error">> {{ $message }}</div>
                @enderror
                <br /><br />
            </form>
        </main>
    </section>
@endsection
