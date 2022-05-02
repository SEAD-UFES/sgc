@extends('layouts.basic')

@section('title', 'Importar Documentos de Colaborador')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Colaboradores</li>
            <li class="breadcrumb-item active" aria-current="page">Importar Documentos de Colaborador</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-xxl-8">
                    @include('_components.alerts')
                    <form action={{ route('employeesDocuments.storeMany1') }} method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="inputFile1" class="form-label">Selecione os arquivos</label>
                            <input class="form-control" type="file" name="files[]" multiple accept="image/*,.pdf" id="inputFile1">
                        </div>
                        @if ($errors->has('files'))
                            @foreach ($errors->get('files') as $error)
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $error }}</strong>
                                </span>
                            @endforeach
                        @endif
                        <div class="mb-3">
                            <label for="selectEmployee1" class="form-label">Colaborador*</label>
                            <select name="employee_id" id="selectEmployee1" class="form-select">
                                <option value="">Selecione o colaborador</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ $employee->id == $id ? 'selected' : '' }}>
                                        {{ $employee->name . ' - ' . $employee->cpf }}</option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <div class="text-danger">> {{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar arquivos</button> <button type="button"
                            onclick="history.back()" class="btn btn-secondary">Cancelar</button>
                        @error('noStore')
                            <div class="text-danger">> {{ $message }}</div>
                        @enderror
                    </form>
                    <br />
                    <p>Formato esperado de arquivos: pdf, jpeg, png, jpg</p>
                    <br />
                </div>
            </div>
        </main>
    </section>
@endsection
