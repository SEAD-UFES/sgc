@extends('layouts.basic')

@section('title', 'Exibir Vínculo')

@section('content')
    <section>
        <strong>Exibir Vínculo</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            {{-- @dd($documents) --}}
            @component('bond.componentBondDetails', compact('bond'))@endcomponent
            <h4>> Documentos</h4><br />
            @component('bond.document.componentList', compact('documents'))@endcomponent
            <br />
            <button type="button" onclick="history.back()">Voltar</button>
            <br /><br /><br />
        </main>
    </section>
@endsection
