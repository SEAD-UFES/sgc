@extends('layouts.basic')

@section('title', 'Editar Atribuição de Papel')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Atribuições de Papel</li>
            <li class="breadcrumb-item active" aria-current="page">Editar</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-xxl-8">
                    @include('_components.alerts')
                    <form action={{ route('responsibilities.update', $responsibility) }} method="POST">
                        @method('PATCH')
                        @component('responsibility.componentResponsibilityForm', compact('users', 'userTypes', 'courses', 'responsibility'))@endcomponent
                        <button type="submit" class="btn btn-primary">Cadastrar</button>
                        <a href="{{ route('responsibilities.index') }}" class="btn btn-secondary">Cancelar</a>
                        @error('noStore')
                            <div class="error">> {{ $message }}</div>
                        @enderror
                        <br/>
                        <br/>
                    </form>
                </div>
            </div>
        </main>
    </section>
@endsection
