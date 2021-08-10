@extends('layouts.basic')

@section('title', 'Editar Vínculo')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item"><a href="{{ route('employee') }}">Colaboradores</a></li>
            <li class="breadcrumb-item"><a href="{{ route('bonds.index') }}">Listar Vínculos</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar vínculo:
                [{{ $bond->employee->name . '-' . $bond->role->name . '-' . $bond->course->name . '-' . $bond->pole->name }}]
            </li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <form action="{{ route('bonds.update', $bond->id) }}" method="POST">
                @method('PATCH')
                @component('bond.componentBondForm', compact('employees', 'roles', 'courses', 'poles', 'bond'))@endcomponent
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
