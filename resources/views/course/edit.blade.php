@extends('layouts.basic')

@section('title', 'Editar Curso')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Sistema</li>
            <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">Listar Cursos</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar: {{ $course->name }}</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-xxl-8">
                    @include('_components.alerts')
                    <form action={{ route('courses.update', $course->id) }} method="POST">
                        @method('PATCH')
                        @component('course.componentCourseForm', compact('course', 'degrees'))@endcomponent
                        <br />
                        <button type="submit" class="btn btn-primary">Atualizar</button>
                        <a href="{{ route('courses.index') }}" class="btn btn-secondary">Cancelar</a>
                        @error('noStore')
                            <div class="text-danger">> {{ $message }}</div>
                        @enderror
                        <br /><br />
                    </form>
                </div>
            </div>
        </main>
    </section>
@endsection
