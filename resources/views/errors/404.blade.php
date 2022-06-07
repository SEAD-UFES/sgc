@extends('layouts.basic')

@section('title', 'Não encontrado')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Sistema</li>
            <li class="breadcrumb-item active" aria-current="page">Não encontrado</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-xxl-8">
                    <h4>Não encontrado [Código 404]</h4>
                    <p>O recurso (ou página) solicitado não foi encontrado.</p>
                    <p>Verifique se o endereço está correto e tente novamente.</p>
                    <p>Ou clique <a href="{{ route('root') }}"><strong>aqui</strong></a> para ir para o início.</p>
                </div>
            </div>
        </main>
    </section>
@endsection