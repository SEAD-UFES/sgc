@extends('layouts.basic')

@section('title', 'Listar Documentos de Termos e Licença')

@section('content')
    <section>
        <strong>Listar Documentos de Termos e Licença</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p style="color: green; font-weight: bold">{{ $message }}</p>
                </div><br />
            @endif
            <table>
                <thead>{{-- <th>@sortablelink('employee.name', 'Colaborador')</th>
                    <th>@sortablelink('role.name', 'Atribuição')</th>
                    <th>@sortablelink('course.name', 'Curso')</th>
                    <th>@sortablelink('pole.name', 'Polo')</th> --}}
                    <th>Colaborador</th>
                    <th>Atribuição</th>
                    <th>Curso</th>
                    <th>Polo</th>
                    <th>Nome</th>
                </thead>
                <tbody>
                    @foreach ($documents as $document)
                        <tr>
                            <td>{{ $document->bond->employee->name }}</td>
                            <td>{{ $document->bond->role->name }}</td>
                            <td>{{ $document->bond->course->name }}</td>
                            <td>{{ $document->bond->pole->name }}</td>
                            <td><a href={{ route('documents.show', ['id' => $document->id, 'type' => 'BondDocument', 'htmlTitle' => $document->original_name]) }}
                                    target="_blank">{{ $document->original_name }}</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <br />
            <button type="button" onclick="history.back()">Voltar</button>
            <br /><br /><br />
        </main>
    </section>
@endsection
