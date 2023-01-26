@extends('layouts.basic')

@section('title', 'Cadastrar Vínculo')

@section('headerScripts')
    <script src="{{ asset('js/enable_searchable_select.js') }}"></script>
@endsection

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Colaboradores</li>
            <li class="breadcrumb-item active" aria-current="page">Cadastrar Vínculo</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-xxl-8">
                    @include('_components.alerts')
                    <form action={{ route('bonds.store') }} method="POST">
                        @component('bond.componentBondForm', compact('employees', 'roles', 'courses', 'courseClasses', 'poles', 'knowledgeAreas'))@endcomponent
                        <br />
                        <button type="submit" class="btn btn-primary">Cadastrar</button>
                        <a href="{{ route('bonds.index') }}" class="btn btn-secondary">Cancelar</a>
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
