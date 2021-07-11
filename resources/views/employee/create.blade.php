@extends('layouts.basic')

@section('title', 'Cadastrar Funcionário')

@section('content')
    <section>
        <strong>Cadastrar Colaborador</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <form action={{ route('employees.store') }} method="POST">
                @component('employee.componentEmployeeForm',  compact('genders', 'birthStates', 'idTypes', 'maritalStatuses', 'addressStates', 'employee'))@endcomponent
                <button type="submit">Cadastrar</button> <button type="button" onclick="history.back()">Cancelar</button>
                @error('noStore')
                    <div class="error">> {{ $message }}</div>
                @enderror
                <br /><br />
            </form>
        </main>
    </section>
@endsection
