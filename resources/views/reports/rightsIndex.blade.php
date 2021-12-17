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
                                ['label' => 'Colaborador', 'value' => 'bondEmployeeNameContains', 'selected' => true], 
                                ['label' => 'Função', 'value' => 'bondRoleNameContains'], 
                                ['label' => 'Curso', 'value' => 'bondCourseNameContains'],
                                ['label' => 'Polo', 'value' => 'bondPoleNameContains'],  
                                ['label' => 'Nome do arquivo', 'value' => 'originalnameContains'], 
                            ], 
                        ]
                    )@endcomponent
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <th>@sortablelink('employee_name', 'Colaborador')</th>
                                <th>@sortablelink('role_name', 'Função')</th>
                                <th>@sortablelink('course_name', 'Curso')</th>
                                <th>@sortablelink('pole_name', 'Polo')</th>
                                <th>@sortablelink('original_name', 'Nome')</th>
                            </thead>
                            <tbody>
                                @foreach ($documents as $document)
                                    <tr>
                                        <td>{{ $document->employee_name }}</td>
                                        <td>{{ $document->role_name }}</td>
                                        <td>{{ $document->course_name }}</td>
                                        <td>{{ $document->pole_name }}</td>
                                        <td><a href={{ route('documents.show', ['id' => $document->id, 'type' => 'BondDocument', 'htmlTitle' => $document->original_name]) }}
                                                target="_blank">{{ $document->original_name }}</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <br />
                    {!! $documents->links() !!}
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