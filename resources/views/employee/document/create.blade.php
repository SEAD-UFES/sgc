@extends('layouts.basic')

@section('title', 'Importar Documento de Colaborador')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Colaboradores</li>
            <li class="breadcrumb-item active" aria-current="page">Importar Documento de Colaborador</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-xxl-8">
                    @include('_components.alerts')
                    <form action={{ route('employees_documents.store') }} method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="inputFile1" class="form-label">Selecione o arquivo</label>
                            <input class="form-control" type="file" name="file" id="inputFile1" accept="image/*,.pdf">
                        </div>

                        <div class="mb-3">
                            <label for="selectType1" class="form-label">Tipo de Documento*</label>
                            <select name="document_type_id" id="selectType1" class="form-select">
                                <option value="">Selecione o tipo</option>
                                @foreach ($documentTypes as $documentType)
                                    <option value="{{ $documentType->id }}">{{ $documentType->name }}</option>
                                @endforeach
                            </select>
                            @error('document_type_id')
                                <div class="text-danger">> {{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="selectEmployee1" class="form-label">Colaborador*</label>
                            <select name="employee_id" id="selectEmployee1" class="form-select">
                                <option value="">Selecione o colaborador</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">
                                        {{ isset($employee->cpf) ? $employee->name . ' - ' . preg_replace('~(\d{3})(\d{3})(\d{3})(\d{2})~', '$1.$2.$3-$4', $employee->cpf) : $employee->name . ' - N/D' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <div class="text-danger">> {{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Enviar arquivo</button>
                        <a href="{{ route('employees_documents.index') }}" class="btn btn-secondary">Cancelar</a>
                        @can('employeeDocument-store')
                            <a href="{{ route('employees_documents.create_many') }}" class="btn btn-warning">Desejo importar vários documentos de colaborador</a>
                        @endcan

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
