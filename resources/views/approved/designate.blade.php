@extends('layouts.basic')

@section('title', 'Nomear Aprovado')

@section('content')
    <section>
        <strong>Nomear Aprovado</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <form action={{ route('employees.store') }} method="POST">
                @method('POST')
                @component('employee.componentEmployeeForm',  compact('genders', 'birthStates', 'documentTypes', 'maritalStatuses', 'addressStates', 'employee'))@endcomponent
                <button type="submit">Cadastrar</button> <button type="button" onclick="history.back()">Cancelar</button>
                @error('noStore')
                    <div class="error">> {{ $message }}</div>
                @enderror
                <br /><br />
            </form>
        </main>
    </section>
@endsection
