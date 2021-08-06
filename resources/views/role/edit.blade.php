@extends('layouts.basic')

@section('title', 'Editar Atribuição')

@section('content')
    <section>
        <h2>Editar Atribuição</h2>
    </section>
    <section id="pageContent">
        <main role="main">
            <form action={{ route('roles.update', $role->id) }} method="POST">
                @method('PATCH')
                @component('role.componentRoleForm', compact('role', 'grantTypes'))@endcomponent
                <button type="submit" class="btn btn-primary">Atualizar</button> <button type="button" onclick="history.back()" class="btn btn-secondary">Cancelar</button>
                @error('noStore')
                    <div class="error">> {{ $message }}</div>
                @enderror
                <br /><br />
            </form>
        </main>
    </section>
@endsection
