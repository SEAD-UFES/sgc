@extends('layouts.basic')

@section('title', 'Editar Atribuição')

@section('content')
    <section>
        <strong>Editar Polo</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <form action={{ route('poles.update', $pole->id) }} method="POST">
                @method('PATCH')
                @component('pole.componentPoleForm', compact('pole'))@endcomponent
            </form>
        </main>
    </section>
@endsection
