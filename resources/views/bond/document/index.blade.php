@extends('layouts.basic')

@section('title', 'Listar Documentos de Vínculos')

@section('content')
    <section>
        <strong>Listar Documentos de Vínculos</strong>
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
            <button type="button" onclick="history.back()">Voltar</button>
            <br /><br /><br />
        </main>
    </section>
@endsection
