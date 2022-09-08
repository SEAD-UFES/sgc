@extends('layouts.basic')

@section('title', 'Exibir Atribuição de Papel')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Sistema</li>
            <li class="breadcrumb-item"><a href="{{ route('responsibilities.index') }}">Listar Atribuições de Papel</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $responsibility->user->email . '->' . $responsibility->userType->name }}</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-xxl-8">
                    
                    @include('_components.alerts')

                    <div class="card mb-3">
                        <div class="card-header" data-bs-toggle="collapse" href="#responsibilityPersonalDataContent" role="button" aria-expanded="true" aria-controls="responsibilityPersonalDataContent">
                            <h4 class='mb-0'>Dados da Atribuição de Papel</h4>
                        </div>
                        <div class="collapse show" id="responsibilityPersonalDataContent" >
                            <div class="card-body">
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Usuário:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ $responsibility->user->email ?? '-' }}</div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Papel:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ $responsibility->userType->name ?? '-' }}
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Curso:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ $responsibility->course->name ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('responsibilities.index') }}" class="btn btn-secondary">Lista de Atribuições de Papel</a>
                    <br/>
                </div>
            </div>
        </main>
    </section>
@endsection
