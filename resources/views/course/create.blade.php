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
            </form>
        </main>
    </section>
@endsection
