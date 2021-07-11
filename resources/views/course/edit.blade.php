@extends('layouts.basic')

@section('title', 'Editar Curso')

@section('content')
    <section>
        <strong>Editar Curso</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <form action={{ route('courses.update', $course->id) }} method="POST">
                @method('PATCH')
                @component('course.componentCourseForm', compact('course', 'courseTypes'))@endcomponent
                <button type="submit">Atualizar</button> <button type="button" onclick="history.back()">Cancelar</button>
                @error('noStore')
                    <div class="error">> {{ $message }}</div>
                @enderror
                <br /><br />
            </form>
        </main>
    </section>
@endsection
