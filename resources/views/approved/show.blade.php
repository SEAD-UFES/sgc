@extends('layouts.basic')

@section('title', 'Exibir Funcionário')

@section('content')
    <section>
        <strong>Exibir Colaborador</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            @component('approved.componentapprovedDetails',  compact('approved'))@endcomponent
        </main>
    </section>
@endsection
