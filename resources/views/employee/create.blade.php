@extends('layouts.basic')

@section('title', 'Cadastrar Funcionário')

@section('content')
    <section>
        <strong>Cadastrar Colaborador</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <form action={{ route('employees.store') }} method="POST">
                {{-- @dd($genders) --}}
                @component('employee.componentEmployeeForm',  compact('genders', 'birthStates', 'idTypes', 'maritalStatuses', 'addressStates', 'employee'))@endcomponent
            </form>
        </main>
    </section>
@endsection
