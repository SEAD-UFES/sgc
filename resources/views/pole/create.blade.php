@extends('layouts.basic')

@section('title', 'Cadastrar Polo')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Sistema</li>
            <li class="breadcrumb-item active" aria-current="page">Cadastrar Polo</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-xxl-8">
                    @include('_components.alerts')
                    <form action={{ route('poles.store') }} method="POST">
                        @component('pole.componentPoleForm')@endcomponent
                        <br />
                        <button type="submit" class="btn btn-primary">Cadastrar</button> <button type="button"
                            onclick="history.back()" class="btn btn-secondary">Cancelar</button>
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
