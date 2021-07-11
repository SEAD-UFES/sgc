@extends('layouts.basic')

@section('title', 'Exibir Funcionário')

@section('content')
    <section>
        <strong>Exibir Colaborador</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            @component('employee.componentEmployeeDetails',  compact('employee'))@endcomponent
        </main>
    </section>
@endsection
