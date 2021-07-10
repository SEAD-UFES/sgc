@extends('layouts.basic')

@section('title', 'Cadastrar Polo')

@section('content')
    <section>
        <strong>Cadastrar Polo</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <form action={{ route('poles.store') }} method="POST">
                @component('pole.componentPoleForm',  compact('pole'))@endcomponent
            </form>
        </main>
    </section>
@endsection
