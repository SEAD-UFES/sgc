@extends('layouts.basic')

@section('title', 'Cadastrar Curso')

@section('content')
    <section>
        <strong>Cadastrar Curso</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <form action={{ route('courses.store') }} method="POST">
                @component('course.componentCourseForm',  compact('course', 'courseTypes'))@endcomponent
                <button type="submit">Cadastrar</button> <button type="button" onclick="history.back()">Cancelar</button>
                @error('noStore')
                    <div class="error">> {{ $message }}</div>
                @enderror
                <br /><br />
            </form>
        </main>
    </section>
@endsection
