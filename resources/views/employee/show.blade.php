@extends('layouts.basic')

@section('title', 'Exibir Funcionário')

@section('content')
    <section>
        <h2>Colaborador</h2>
    </section>
    <section id="pageContent">
        <main role="main">
            @component('employee.componentEmployeeDetails',  compact('employee'))@endcomponent
        </main>
    </section>
@endsection
