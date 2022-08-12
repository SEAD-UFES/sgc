@extends('layouts.basic')

@section('title', 'Proibido')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Sistema</li>
            <li class="breadcrumb-item active" aria-current="page">Proibido</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-xxl-8">
                    <h4>Proibido [Código 403]</h4>
                    <p>O papel atual do usuário não tem permissão para acessar este recurso (ou página).</p>
                    <p>Verifique se o endereço digitado e o papel atual estão corretos e tente novamente.</p>
                    <p>Ou clique <a href="{{ route('root') }}"><strong>aqui</strong></a> para ir para o início.</p>
                </div>
            </div>
        </main>
    </section>
@endsection