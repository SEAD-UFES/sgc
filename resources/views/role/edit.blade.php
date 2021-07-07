@extends('layouts.basic')

@section('title', 'Editar Atribuição')

@section('content')
    <section>
        <strong>Editar Atribuição</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <form action={{ route('roles.update', $role->id) }} method="POST">
                @method('PATCH')
                @component('role.componentRoleForm', compact('role', 'grantTypes'))@endcomponent
            </form>
        </main>
    </section>
@endsection
