@extends('layouts.basic')

@section('title', 'Documentos de Colaboradores')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Colaboradores</li>
            <li class="breadcrumb-item active" aria-current="page">Listar Documentos de Colaboradores</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-xxl-8">
                    @include('_components.alerts')

                    {{-- filtros --}}
                    @component(
                        '_components.filters_form', 
                        [
                            'filters' =>$filters,
                            'options' => [
                                [ 'label'=>'CPF', 'value'=>'employee_cpf_contains', 'selected'=>true],
                                [ 'label'=>'Colaborador', 'value'=>'employee_name_contains'],
                                [ 'label'=>'Nome do arquivo', 'value'=>'originalname_contains'],
                                [ 'label'=>'Tipo', 'value'=>'documentType_name_contains'],
                            ]
                        ]
                    )@endcomponent
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <th>@sortablelink('employee.cpf', 'CPF')</th>
                                <th>@sortablelink('employee.name', 'Colaborador')</th>
                                <th>@sortablelink('original_name', 'Nome do arquivo')</th>
                                <th>@sortablelink('documentType.name', 'Tipo')</th>
                            </thead>
                            <tbody>
                                @foreach ($documents as $document)
                                    <tr>
                                        <td>
                                            <a href="{{ route('employees.show', $document->employee->id) }}" target="_blank">
                                                {{ $document->employee->cpf }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('employees.show', $document->employee->id) }}" target="_blank">
                                                {{ $document->employee->name }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('documents.show', ['id' => $document->id, 'type' => 'EmployeeDocument', 'htmlTitle' => $document->original_name]) }}"
                                                target="_blank">{{ $document->original_name }}</a>
                                        </td>
                                        <td>{{ $document->documentType->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <button type="button" onclick="history.back()" class="btn btn-secondary">Voltar</button>
                    @can('employeeDocument-store')
                        <a href="{{ route('employees.document.create') }}" class="btn btn-warning">Importar novos Documentos de Colaborador</a>
                    @endcan
                    <br /><br />
                </div>
            </div>
        </main>
    </section>
@endsection

@section('scripts')
    @component('_components.filters_script', ['filters' =>$filters] )@endcomponent
@endsection