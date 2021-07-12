@extends('layouts.basic')

@section('title', 'Editar Funcionário')

@section('content')
    <section>
        <strong>Editar Colaborador</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <form action="{{ route('approveds.update', $approved->id) }}" method="POST">
                @method('PATCH')
                @component('approved.componentApprovedForm',  compact('genders', 'birthStates', 'idTypes', 'maritalStatuses', 'addressStates', 'approved'))@endcomponent
                <button type="submit">Atualizar</button> <button type="button" onclick="history.back()">Cancelar</button>
                @error('noStore')
                    <div class="error">> {{ $message }}</div>
                @enderror
                <br /><br />
            </form>
        </main>
    </section>
@endsection
