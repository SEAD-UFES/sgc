@extends('layouts.basic')

@section('title', 'Documentos de Vínculos')

@section('content')
    <section>
        <h2>Documentos de Vínculos</h2>
    </section>
    <section id="pageContent">
        <main role="main">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p style="color: green; font-weight: bold">{{ $message }}</p>
                </div><br />
            @endif
            @component('bond.document.componentList', compact('documents'))@endcomponent
            <br />
            <button type="button" onclick="history.back()" class="btn btn-secondary">Voltar</button>
            <br /><br /><br />
        </main>
    </section>
@endsection
