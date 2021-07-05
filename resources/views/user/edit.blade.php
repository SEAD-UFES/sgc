@extends('layouts.basic')

@section('title', 'Editar Usuário')

@section('content')
    <section>
        <strong>Editar Usuário</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <form action="{{ route('user.update', $user->id) }}" method="POST">
                @method('PATCH')
                @component('user.componentUserForm', compact('user', 'roles'))@endcomponent
            </form>
        </main>
    </section>
@endsection
