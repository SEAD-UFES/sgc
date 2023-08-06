@extends('layouts.basic')

@section('title', 'Disciplinas')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Sistema</li>
            <li class="breadcrumb-item active" aria-current="page">Listar Disciplinas</li>
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
                                ['label' => 'Nome', 'value' => 'nameContains', 'selected' => true],
                                ['label' => 'Curso', 'value' => 'courseNameContains'],
                                ['label' => 'Código', 'value' => 'codeContains'],
                                ['label' => 'PPC', 'value' => 'cppContains']
                            ], 
                        ]
                    )@endcomponent
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <th>@sortablelink('name', 'Nome')</th>
                                <th>@sortablelink('course.name', 'Curso')</th>
                                <th>@sortablelink('code', 'Código')</th>
                                <th>@sortablelink('cpp', 'PPC')</th>
                                <th class="text-center">Ações</th>
                            </thead>
                            <tbody>
                                @foreach ($courseClasses as $courseClass)
                                    <tr>
                                        <td>{{ $courseClass->name }}</td>
                                        <td>{{ $courseClass->course->name }}</td>
                                        <td>{{ $courseClass->code }}</td>
                                        <td>{{ $courseClass->cpp }}</td>
                                        <td class="text-center"><div class="d-inline-flex">
                                            @can('course-update')
                                                <a href="{{ route('course-classes.edit', $courseClass) }}" data-bs-toggle="tooltip" title="Editar disciplina" class="btn btn-primary btn-sm">
                                                    <i class="bi-pencil-fill"></i>
                                                </a>&nbsp;
                                            @endcan
                                            @can('course-destroy')
                                                <form name="{{ 'formDelete' . $courseClass->id }}" action="{{ route('course-classes.destroy', $courseClass) }}"
                                                    method="POST">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="button" data-bs-toggle="tooltip" title="Excluir disciplina" 
                                                        onclick="{{ 'if(confirm(\'Tem certeza que deseja excluir essa Discilplina?\')) document.forms[\'formDelete' . $courseClass->id . '\'].submit();' }}" class="btn btn-danger btn-sm">
                                                        <i class="bi-trash-fill"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <br />
                    {!! $courseClasses->links() !!}
                    <a href="{{ route('home') }}" class="btn btn-secondary">Voltar para o Início</a>
                    @can('course-store')
                        <a href="{{ route('course-classes.create') }}" class="btn btn-warning">Cadastrar nova Disciplina</a>
                    @endcan
                    <br /><br />
                </div>
            </div>
        </main>
    </section>
@endsection

@section('scripts')
    @component('_components.filters_script', ['filters' => $filters])@endcomponent
@endsection
