@extends('layouts.basic')

@section('title', 'Não autorizado')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Sistema</li>
            <li class="breadcrumb-item active" aria-current="page">Não autorizado</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-xxl-8">
                    <h4>Não autorizado [Código 401]</h4>
                    <p>Credenciais de usuário inválidas.</p>
                    <p>Clique <a href="{{ route('root') }}"><strong>aqui</strong></a> para ir para o início.</p>
                </div>
            </div>
        </main>
    </section>
@endsection