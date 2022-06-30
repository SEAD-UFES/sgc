@extends('layouts.basic')

@section('title', 'Alterar senha')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Sistema</li>
            <li class="breadcrumb-item active" aria-current="page">Alterar senha: {{ $user->email }}</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-xxl-8">
                    @include('_components.alerts')
                    <form action="{{ route('users.currentPasswordUpdate') }}" method="POST">
                        @method('PATCH')
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">E-Mail</label>
                            <div class="col-sm-8 col-lg-9">{{ $user->email ?? '-' }}</div>
                        </div>
                        <div class="mb-3">
                            <label for="inputPassword1" class="form-label">Nova Senha</label>
                            <input name="password" type="password" autocomplete="new-password" id="inputPassword1"
                                class="form-control" placeholder="Nova Senha" />
                            @error('password')
                                <div class="text-danger">> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="inputConfirmPassword1" class="form-label">Confirme a Nova Senha</label>
                            <input name="confirmPassword" type="password" autocomplete="new-password" id="inputConfirmPassword1"
                                class="form-control" placeholder="Confirme Nova Senha" />
                            @error('confirmPassword')
                                <div class="text-danger">> {{ $message }}</div>
                            @enderror
                        </div>
                        <br />
                        <button type="submit" class="btn btn-primary">Atualizar</button>
                        <a href="{{ route('home') }}" class="btn btn-secondary">Cancelar</a>
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
