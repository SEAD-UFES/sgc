@extends('layouts.basic')

@section('title', 'Editar Atribuição')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Sistema</li>
            <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Listar Atribuições</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar: {{ $role->name }}</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <form action={{ route('roles.update', $role->id) }} method="POST">
                @method('PATCH')
                @component('role.componentRoleForm', compact('role', 'grantTypes'))@endcomponent
                <button type="submit" class="btn btn-primary">Atualizar</button> <button type="button"
                    onclick="history.back()" class="btn btn-secondary">Cancelar</button>
                @error('noStore')
                    <div class="error">> {{ $message }}</div>
                @enderror
                <br /><br />
            </form>
        </main>
    </section>
@endsection
