@extends('layouts.basic')

@section('title', 'Cadastrar Vínculo')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item"><a href="{{ route('employee') }}">Colaboradores</a></li>
            <li class="breadcrumb-item active" aria-current="page">Cadastrar Vínculo</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <form action={{ route('bonds.store') }} method="POST">
                @component('bond.componentBondForm', compact('employees', 'roles', 'courses', 'poles', 'bond'))@endcomponent
                <button type="submit" class="btn btn-primary">Cadastrar</button> <button type="button"
                    onclick="history.back()" class="btn btn-secondary">Cancelar</button>
                @error('noStore')
                    <div class="error">> {{ $message }}</div>
                @enderror
                <br /><br />
            </form>
        </main>
    </section>
@endsection
