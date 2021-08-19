@extends('layouts.basic')

@section('title', 'Cadastrar Atribuição de Papel')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Atribuições de Papel</li>
            <li class="breadcrumb-item active" aria-current="page">Cadastrar Atribuições de Papel</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-xxl-8">
                    <form action={{ route('userTypeAssignments.store') }} method="POST">
                        @component('userTypeAssignment.componentBondForm', compact('users', 'userTypes', 'courses', 'userTypeAssignment'))@endcomponent
                        <button type="submit" class="btn btn-primary">Cadastrar</button> <button type="button"
                            onclick="history.back()" class="btn btn-secondary">Cancelar</button>
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