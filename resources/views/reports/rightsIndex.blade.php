@extends('layouts.basic')

@section('title', 'Documentos de Termos e Licença')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item"><a href="{{ route('employee') }}">Relatórios</a></li>
            <li class="breadcrumb-item active" aria-current="page">Listar Documentos de Termos e Licença</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p style="color: green; font-weight: bold">{{ $message }}</p>
                </div><br />
            @endif
            <table class="table table-striped table-hover">
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
            <button type="button" onclick="history.back()" class="btn btn-secondary">Voltar</button>
            <br /><br />
        </main>
    </section>
@endsection
