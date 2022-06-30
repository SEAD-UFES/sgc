@extends('layouts.basic')

@section('title', 'Cadastrar Usuário')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Sistema</li>
            <li class="breadcrumb-item active" aria-current="page">Cadastrar Usuário</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-xxl-8">
                    @include('_components.alerts')
                    <form action={{ route('users.store') }} method="POST">
                        @component('user.componentUserForm', compact('userTypes'))@endcomponent
                        <br />
                        <button type="submit" class="btn btn-primary">Cadastrar</button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
                            <br /><br />
                        @error('noStore')
                            <div class="text-danger">> {{ $message }}</div>
                        @enderror
                    </form>
                </div>
            </div>
        </main>
    </section>
@endsection
