@extends('layouts.basic')

@section('title', 'Cadastrar Detalhes Institucionais')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Colaboradores</li>
            <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Listar Colaboradores</a></li>
            <li class="breadcrumb-item active" aria-current="page">Cadastrar Detalhes Institucionais: {{ $employee->email }}</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-xxl-8">
                    @include('_components.alerts')
                    <form action={{ route('institutionalDetails.store') }} method="POST">
                        @component('institutionalDetail.componentInstitutionalDetailForm')@endcomponent
                        <br />
                        <button type="submit" class="btn btn-primary">Cadastrar</button>
                        <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-secondary">Cancelar</a>
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
