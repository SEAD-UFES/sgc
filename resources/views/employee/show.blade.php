@extends('layouts.basic')

@section('title', 'Exibir Colaborador')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Colaboradores</li>
            <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Listar Colaboradores</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $employee->name }}</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-xxl-8">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="form-tab" data-bs-toggle="tab" data-bs-target="#form" type="button" role="tab" aria-controls="form" aria-selected="true">Formulário</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="more-tab" data-bs-toggle="tab" data-bs-target="#more" type="button" role="tab" aria-controls="more" aria-selected="false">Mais</button>
                        </li>
                    </ul>
                    <div class="tab-content border-start border-end border-bottom" id="myTabContent">
                        <div class="tab-pane fade show active" id="form" role="tabpanel" aria-labelledby="form-tab">
                            @component('employee.componentEmployeeDetails', compact('employee'))@endcomponent
                        </div>
                        <div class="tab-pane fade" id="more" role="tabpanel" aria-labelledby="more-tab">
                            <br />
                            &nbsp;
                            <a class="btn btn-primary" href="{{ route('employees.document.index', ['employee_cpf_contains[0]' => $employee->cpf]) }}" target="_blank">Ver Documentos</a>
                            &nbsp;
                            <a class="btn btn-primary" href="{{ route('bonds.index', ['employee_cpf_contains[0]' => $employee->cpf]) }}" target="_blank">Ver Vínculos</button></a>
                            <br /><br />
                        </div>
                    </div>
                    <br />
                    <button type="button" onclick="history.back()" class="btn btn-secondary">Voltar</button>
                    <br /><br />
                </div>
            </div>
        </main>
    </section>
@endsection
