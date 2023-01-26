@extends('layouts.basic')

@section('title', 'Exibir Curso')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Cursos</li>
            <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">Listar Cursos</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $course->name }}</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-xxl-8">
                    
                    @include('_components.alerts')

                    <h4>Curso: {{ $course->name }}</h4>

                    <div class="card mb-3">
                        <div class="card-header" data-bs-toggle="collapse" href="#courseDataContent" role="button" aria-expanded="true" aria-controls="courseDataContent">
                            <h4 class='mb-0'>Dados do Curso</h4>
                        </div>
                        <div class="collapse show" id="courseDataContent" >
                            <div class="card-body">
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Nome:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ $course->name ?? '-' }}</div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Descrição:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ $course->description ?? '-' }}</div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Tipo:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ $course->degree->label() }}</div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Endereço do AVA:</strong></div>
                                    <div class="col-sm-8 col-lg-9">
                                        @if($course->lms_url != null)
                                            <a href="{{ $course->lms_url }}" target="_blank">{{ $course->lms_url }}</a>
                                        @else
                                            - 
                                        @endif
                                    </div>
                                </div>
                        
                                <div class="">
                                    @can('course-update', $course)
                                        <a href="{{ route('courses.edit', $course->id) }}" data-bs-toggle="tooltip" title="Editar curso" class="btn btn-primary btn-sm">
                                            <i class="bi-pencil-fill"></i> Editar dados do curso
                                        </a>&nbsp;
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header collapsed" data-bs-toggle="collapse" href="#courseClassListContent" role="button" aria-expanded="false" aria-controls="courseClassListContent">
                            <h4 class='mb-0'>Disciplinas</h4>
                        </div>
                        <div class="collapse" id="courseClassListContent" >
                            <div class="card-body">
                                @if($course->courseClasses->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <th>Nome</th>
                                                <th>Código</th>
                                                <th>PPC</th>
                                                <th class="text-center">Ações</th>
                                            </thead>
                                            <tbody>
                                                @foreach ($course->courseClasses as $courseClass)
                                                    <tr>
                                                        <td>
                                                            {{ $courseClass->name }}
                                                        </td>
                                                        <td>
                                                            {{ $courseClass->code }}
                                                        </td>
                                                        <td>
                                                            {{ $courseClass->cpp }}
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="d-inline-flex">
                                                                @can('course-update', $course)
                                                                    <a href="{{ route('course-classes.edit', $courseClass->id) }}" data-bs-toggle="tooltip" title="Editar Disciplina" class="btn btn-primary btn-sm">
                                                                        <i class="bi-pencil-fill"></i>
                                                                    </a>&nbsp;
                                                                @endcan
                                                                @can('course-destroy')
                                                                    <form name="{{ 'formDelete' . $courseClass->id }}"
                                                                        action="{{ route('course-classes.destroy', $courseClass) }}" method="POST">
                                                                        @method('DELETE')
                                                                        @csrf
                                                                        <button type="button" data-bs-toggle="tooltip" title="Excluir" 
                                                                            onclick="{{ 'if(confirm(\'Tem certeza que deseja excluir essa Disciplina?\')) document.forms[\'formDelete' . $courseClass->id . '\'].submit();' }}" class="btn btn-danger btn-sm">
                                                                            <i class="bi-trash-fill"></i>
                                                                        </button>
                                                                    </form>
                                                                @endcan
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="mb-0">O curso não possui disciplinas cadastradas.</p>
                                @endif
                                <div class="">
                                    <a class="btn btn-primary btn-sm" href="{{ route('course-classes.index', ['courseNameContains[0]' => $course->name]) }}" target="_blank">
                                        Listagem avançada (com todos as disciplinas)...
                                    </a>
                                    @can('course-store')
                                        <a href="{{ route('course-classes.create', ['givenCourse' => $course->id] ) }}" class="btn btn-warning">Cadastrar nova Disciplina</a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('courses.index') }}" class="btn btn-secondary">Lista de Cursos</a>
                    <br/><br/>
                </div>
            </div>
        </main>
    </section>
@endsection
