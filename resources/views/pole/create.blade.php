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
                <button type="submit">Cadastrar</button> <button type="button" onclick="history.back()">Cancelar</button>
                @error('noStore')
                    <div class="error">> {{ $message }}</div>
                @enderror
                <br /><br />
            </form>
        </main>
    </section>
@endsection
