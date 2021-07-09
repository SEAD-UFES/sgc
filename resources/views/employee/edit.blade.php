@extends('layouts.basic')

@section('title', 'Editar Funcionário')

@section('content')
    <section>
        <strong>Editar Colaborador</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <form action="{{ route('employees.update', $employee->id) }}" method="POST">
                @method('PATCH')
                @component('employee.componentEmployeeForm',  compact('genders', 'birthStates', 'idTypes', 'maritalStatuses', 'addressStates', 'employee'))@endcomponent
            </form>
        </main>
    </section>
@endsection
