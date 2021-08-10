@extends('layouts.basic')

@section('title', 'Editar Curso')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item"><a href="{{ route('system') }}">Sistema</a></li>
            <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">Listar Cursos</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar: {{ $course->name }}</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <form action={{ route('courses.update', $course->id) }} method="POST">
                @method('PATCH')
                @component('course.componentCourseForm', compact('course', 'courseTypes'))@endcomponent
                <button type="submit" class="btn btn-primary">Atualizar</button> <button type="button"
                    onclick="history.back()" class="btn btn-secondary">Cancelar</button>
                @error('noStore')
                    <div class="error">> {{ $message }}</div>
                @enderror
                <br /><br />
            </form>
        </main>
    </section>
@endsection
