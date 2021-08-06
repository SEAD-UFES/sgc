@extends('layouts.basic')

@section('title', 'Editar Funcionário')

@section('content')
    <section>
        <h2>Editar Colaborador</h2>
    </section>
    <section id="pageContent">
        <main role="main">
            <form action="{{ route('employees.update', $employee->id) }}" method="POST">
                @method('PATCH')
                @component('employee.componentEmployeeForm',  compact('genders', 'birthStates', 'documentTypes', 'maritalStatuses', 'addressStates', 'employee'))@endcomponent
                <button type="submit" class="btn btn-primary">Atualizar</button> <button type="button" onclick="history.back()" class="btn btn-secondary">Cancelar</button>
                @error('noStore')
                    <div class="error">> {{ $message }}</div>
                @enderror
                <br /><br />
            </form>
        </main>
    </section>
@endsection
