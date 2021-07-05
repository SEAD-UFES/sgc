@extends('layouts.basic')

@section('title', 'Editar Curso')

@section('content')
    <section>
        <strong>Editar Curso</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <form action={{ route('course.update') }} method="POST">
                @component('course.componentUserForm', ['course' => $user])@endcomponent
            </form>
        </main>
    </section>
@endsection
