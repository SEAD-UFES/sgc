@extends('layouts.basic')

@section('title', 'Cadastrar Usuário')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">{{-- <a href="{{ route('system') }}"> --}}Sistema{{-- </a> --}}</li>
            <li class="breadcrumb-item active" aria-current="page">Cadastrar Usuário</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <form action={{ route('users.store') }} method="POST">
                @component('user.componentUserForm', compact('user', 'userTypes'))@endcomponent
                <button type="submit" class="btn btn-primary">Cadastrar</button> <button type="button"
                    onclick="history.back()" class="btn btn-secondary">Cancelar</button>
                    <br /><br />
                @error('noStore')
                    <div class="error">> {{ $message }}</div>
                @enderror
            </form>
        </main>
    </section>
@endsection
