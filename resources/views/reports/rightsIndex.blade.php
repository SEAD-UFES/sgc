@extends('layouts.basic')

@section('title', 'Termos de cessão de direitos')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Relatórios</li>
            <li class="breadcrumb-item active" aria-current="page">Listar Termos de cessão de direitos</li>
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
                                <th>@sortablelink('employeeName', 'Colaborador')</th>
                                <th>@sortablelink('roleName', 'Função')</th>
                                <th>@sortablelink('courseName', 'Curso')</th>
                                <th>@sortablelink('poleName', 'Polo')</th>
                                <th>@sortablelink('file_name', 'Nome')</th>
                            </thead>
                            <tbody>
                                @foreach ($documents as $document)
                                    <tr>
                                        <td>
                                            @can('employee-show')
                                                <a href="{{ route('employees.show', $document->employee_id) }}" target="_blank">
                                            @endcan
                                            {{ $document->employee_name }}
                                            @can('employee-show')
                                                </a>
                                            @endcan
                                        </td>
                                        <td>
                                            @can('bond-show')
                                                <a href="{{ route('bonds.show', $document->bond_id) }}" target="_blank">
                                            @endcan
                                            {{ $document->role_name }}
                                            @can('bond-show')
                                                </a>
                                            @endcan
                                        </td>
                                        <td>{{ $document->course_name ?? '-' }}</td>
                                        <td>{{ $document->pole_name ?? '-' }}</td>
                                        <td><a href={{ route('rights.show', ['id' => $document->id, 'htmlTitle' => $document->file_name]) }}
                                                target="_blank">{{ $document->file_name }}</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <br />
                    {!! $documents->links() !!}
                    <a href="{{ route('home') }}" class="btn btn-secondary">Voltar para o Início</a>
                    <br /><br />
                </div>
            </div>
        </main>
    </section>
@endsection

@section('scripts')
    @component('_components.filters_script', ['filters' => $filters])@endcomponent
@endsection