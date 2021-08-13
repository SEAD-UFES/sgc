@extends('layouts.basic')

@section('title', 'Cadastrar Curso')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">{{-- <a href="{{ route('system') }}"> --}}Sistema{{-- </a> --}}</li>
            <li class="breadcrumb-item active" aria-current="page">Cadastrar Curso</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <form action={{ route('courses.store') }} method="POST">
                @component('course.componentCourseForm', compact('course', 'courseTypes'))@endcomponent
                <button type="submit" class="btn btn-primary">Cadastrar</button> <button type="button"
                    onclick="history.back()" class="btn btn-secondary">Cancelar</button>
                @error('noStore')
                    <div class="error">> {{ $message }}</div>
                @enderror
                <br /><br />
            </form>
        </main>
    </section>
@endsection
