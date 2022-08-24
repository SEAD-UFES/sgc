@extends('layouts.basic')

@section('title', 'Associação de Colaborador')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Sistema</li>
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuários</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar Associação: {{ $user->email }}</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-xxl-8">
                    @include('_components.alerts')
                    <h4>Usuário: {{ $user->email }}</h4>
                    <br />
                    <form action="{{ route('users.updateEmployeeLink', $user->id) }}" method="POST">
                        @method('PATCH')
                        @csrf
                        <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                            <label for="selectEmployeeLink1" class="form-label">Colaborador associado:</label>
                            <select name="employee_id" id="selectEmployeeLink1" class="form-select">
                                <option value="">Nenhum</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}"
                                        {{ isset($user) ? ($user->employee_id == $employee->id ? 'selected' : '') : (old('employee_id') == $employee->id ? 'selected' : '') }}>
                                        {{ $employee->cpf . ' - ' . $employee->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <div class="text-danger">> {{ $message }}</div>
                            @enderror
                        </div>
                        <br />
                        <button type="submit" class="btn btn-primary">Atualizar</button>
                        <a href="{{ route('users.show', $user) }}" class="btn btn-secondary">Cancelar</a>
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
