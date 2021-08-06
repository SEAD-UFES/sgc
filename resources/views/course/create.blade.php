@extends('layouts.basic')

@section('title', 'Cadastrar Curso')

@section('content')
    <section>
        <h2>Cadastrar Curso</h2>
    </section>
    <section id="pageContent">
        <main role="main">
            <form action={{ route('courses.store') }} method="POST">
                @component('course.componentCourseForm',  compact('course', 'courseTypes'))@endcomponent
                <button type="submit" class="btn btn-primary">Cadastrar</button> <button type="button" onclick="history.back()" class="btn btn-secondary">Cancelar</button>
                @error('noStore')
                    <div class="error">> {{ $message }}</div>
                @enderror
                <br /><br />
            </form>
        </main>
    </section>
@endsection
