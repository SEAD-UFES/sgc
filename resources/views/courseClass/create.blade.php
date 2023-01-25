@extends('layouts.basic')

@section('title', 'Cadastrar Disciplina')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Sistema</li>
            <li class="breadcrumb-item active" aria-current="page">Cadastrar Disciplina</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-xxl-8">
                    @include('_components.alerts')
                    <form action={{ route('course-classes.store') }} method="POST">
                        @component('courseClass.componentCourseClassForm', compact('courses'))@endcomponent
                        <br />
                        <button type="submit" class="btn btn-primary">Cadastrar</button>
                        <a href="{{ route('course-classes.index') }}" class="btn btn-secondary">Cancelar</a>
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
