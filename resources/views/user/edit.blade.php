@extends('layouts.basic')

@section('title', 'Editar Usuário')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Sistema</li>
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Listar Usuários</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar: {{ $user->email }}</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-xxl-8">
                    @include('_components.alerts')
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @method('PATCH')
                        @component('user.componentUserForm', compact('user', 'userTypes'))@endcomponent
                        <br />
                        <button type="submit" class="btn btn-primary">Atualizar</button> <button type="button"
                            onclick="history.back()" class="btn btn-secondary">Cancelar</button>
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
