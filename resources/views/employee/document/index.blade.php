@extends('layouts.basic')

@section('title', 'Documentos de Colaboradores')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item"><a href="{{ route('employee') }}">Colaboradores</a></li>
            <li class="breadcrumb-item active" aria-current="page">Listar Documentos de Colaboradores</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p style="color: green; font-weight: bold">{{ $message }}</p>
                </div><br />
            @endif

            {{-- filtros --}}
            @component(
                '_components.filters_form', 
                [
                    'filters' =>$filters,
                    'options' => [
                        [ 'label'=>'Colaborador', 'value'=>'employee_name_contains', 'selected'=>true],
                        [ 'label'=>'Nome do arquivo', 'value'=>'originalname_contains'],
                        [ 'label'=>'Tipo', 'value'=>'documentType_name_contains'],
                    ]
                ]
            )@endcomponent

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
                            <td><a href={{ route('documents.show', ['id' => $document->id, 'type' => 'EmployeeDocument', 'htmlTitle' => $document->original_name]) }}
                                    target="_blank">{{ $document->original_name }}</a></td>
                            <td>{{ $document->documentType->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="button" onclick="history.back()" class="btn btn-secondary">Voltar</button>
            <br /><br />
        </main>
    </section>
@endsection

@section('scripts')
    @component('_components.filters_script', ['filters' =>$filters] )@endcomponent
@endsection