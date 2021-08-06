@extends('layouts.basic')

@section('title', 'Documentos de Colaboradores')

@section('content')
    <section>
        <h2>Documentos de Colaboradores</h2>
    </section>
    <section id="pageContent">
        <main role="main">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p style="color: green; font-weight: bold">{{ $message }}</p>
                </div><br />
            @endif
            <table class="table table-striped table-hover">
                <thead>
                    <th>@sortablelink('employee.name', 'Colaborador')</th>
                    <th>@sortablelink('original_name', 'Nome do arquivo')</th>
                    <th>@sortablelink('documentType.name', 'Tipo')</th>
                </thead>
                <tbody>
                    @foreach ($documents as $document)
                        <tr>
                            <td>{{ $document->employee->name }}</td>
                            <td><a href={{ route('documents.show', ['id' => $document->id, 'type' => 'EmployeeDocument', 'htmlTitle' => $document->original_name]) }} target="_blank">{{ $document->original_name }}</a></td>
                            <td>{{ $document->documentType->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <br />
        </main>
    </section>
@endsection
