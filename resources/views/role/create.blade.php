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
            </form>
        </main>
    </section>
@endsection
