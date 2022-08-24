@extends('layouts.basic')

@section('title', 'Editar Detalhes Institucionais')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Colaboradores</li>
            <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Listar Colaboradores</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar Detalhes Institucionais: {{ $employee->email }}</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-xxl-8">
                    @include('_components.alerts')
                    @if (isset($institutionalDetail))
                        <form action={{ route('employees.institutionalDetails.update', $employee->id) }} method="POST">
                            @method('PATCH')
                    @else
                        <form action={{ route('employees.institutionalDetails.store', $employee->id) }} method="POST">
                    @endif
                    @component('institutionalDetail.componentInstitutionalDetailForm', compact('institutionalDetail'))
                    @endcomponent
                    <br />
                    <button type="submit" class="btn btn-primary">Atualizar</button>
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
