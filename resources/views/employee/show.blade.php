@extends('layouts.basic')

@section('title', 'Exibir Funcionário')

@section('content')
    <section>
        <strong>Exibir Funcionário</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            @component('employee.componentEmployeeDetails',  compact('employee'))@endcomponent
        </main>
    </section>
@endsection
