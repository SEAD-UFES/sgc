@extends('layouts.basic')

@section('title', 'Cadastrar Vínculo')

@section('content')
    <section>
        <h2>Cadastrar Vínculo</h2>
    </section>
    <section id="pageContent">
        <main role="main">
            <form action={{ route('bonds.store') }} method="POST">
                @component('bond.componentBondForm',  compact('employees', 'roles', 'courses', 'poles', 'bond'))@endcomponent
                <button type="submit" class="btn btn-primary">Cadastrar</button> <button type="button" onclick="history.back()" class="btn btn-secondary">Cancelar</button>
                @error('noStore')
                    <div class="error">> {{ $message }}</div>
                @enderror
                <br /><br />
            </form>
        </main>
    </section>
@endsection
