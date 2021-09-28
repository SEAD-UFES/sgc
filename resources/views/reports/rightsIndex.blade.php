@extends('layouts.basic')

@section('title', 'Documentos de Termos e Licença')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Relatórios</li>
            <li class="breadcrumb-item active" aria-current="page">Listar Documentos de Termos e Licença</li>
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
                            'filters' => $filters,
                            'options' => [
                                ['label' => 'Colaborador', 'value' => 'bond_employee_name_contains', 'selected' => true], 
                                ['label' => 'Função', 'value' => 'bond_role_name_contains'], 
                                ['label' => 'Curso', 'value' => 'bond_course_name_contains'],
                                ['label' => 'Polo', 'value' => 'bond_pole_name_contains'],  
                                ['label' => 'Nome do arquivo', 'value' => 'originalname_contains'], 
                            ], 
                        ]
                    )@endcomponent
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <th>@sortablelink('bondEmployeeName', 'Colaborador')</th>
                                <th>@sortablelink('bondRoleName', 'Função')</th>
                                <th>@sortablelink('bondCourseName', 'Curso')</th>
                                <th>@sortablelink('bondPoleName', 'Polo')</th>
                                <th>@sortablelink('original_name', 'Nome')</th>
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
                    </div>
                    <button type="button" onclick="history.back()" class="btn btn-secondary">Voltar</button>
                    <br /><br />
                </div>
            </div>
        </main>
    </section>
@endsection

@section('scripts')
    @component('_components.filters_script', ['filters' => $filters])@endcomponent
@endsection