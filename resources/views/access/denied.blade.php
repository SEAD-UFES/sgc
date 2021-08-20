@extends('layouts.basic')

@section('title', 'Acesso negado')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Sistema</li>
            <li class="breadcrumb-item active" aria-current="page">Acesso negado</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-xxl-8">
                    <h4>Acesso negado</h4>
                    <p>O papel atual do usuário não tem permissão para acessar este recurso.<p>
                </div>
            </div>
        </main>
    </section>
@endsection
