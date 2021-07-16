@extends('layouts.basic')

@section('title', 'Listar Documentos de Colaboradores')

@section('content')
    <section>
        <strong>Listar Documentos de Colaboradores</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p style="color: green; font-weight: bold">{{ $message }}</p>
                </div><br />
            @endif
            <table>
                <thead>
                    <th>Vínculo</th>
                    <th>Nome</th>
                    <th>Tipo</th>
                </thead>
                <tbody>
                    @foreach ($documents as $document)
                        <tr>
                            <td>{{ $document->employee->name }}</td>
                            <td>{{ $document->original_name }}</td>
                            <td>{{ $document->documentType->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <br />
        </main>
    </section>
@endsection
