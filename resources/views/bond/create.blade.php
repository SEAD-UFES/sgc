@extends('layouts.basic')

@section('title', 'Cadastrar Vínculo')

@section('content')
    <section>
        <strong>Cadastrar Vínculo</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <form action={{ route('bonds.store') }} method="POST">
                @component('bond.componentBondForm',  compact('employees', 'roles', 'courses', 'poles', 'bond'))@endcomponent
                <button type="submit">Cadastrar</button> <button type="button" onclick="history.back()">Cancelar</button>
                @error('noStore')
                    <div class="error">> {{ $message }}</div>
                @enderror
                <br /><br />
            </form>
        </main>
    </section>
@endsection
