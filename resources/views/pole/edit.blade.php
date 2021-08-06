@extends('layouts.basic')

@section('title', 'Editar Atribuição')

@section('content')
    <section>
        <h2>Editar Polo</h2>
    </section>
    <section id="pageContent">
        <main role="main">
            <form action={{ route('poles.update', $pole->id) }} method="POST">
                @method('PATCH')
                @component('pole.componentPoleForm', compact('pole'))@endcomponent
                <button type="submit" class="btn btn-primary">Atualizar</button> <button type="button" onclick="history.back()" class="btn btn-secondary">Cancelar</button>
                @error('noStore')
                    <div class="error">> {{ $message }}</div>
                @enderror
                <br /><br />
            </form>
        </main>
    </section>
@endsection
