@extends('layouts.basic')

@section('title', 'Importar Documento de Colaborador')

@section('content')
    <section>
        <h2>Importar Documento de Colaborador</h2>
    </section>
    <section id="pageContent">
        <main role="main">
            <form action={{ route('employees.document.store') }} method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="inputFile1" class="form-label">Selecione o arquivo</label>
                    <input class="form-control" type="file" name="file" id="inputFile1">
                </div>
                <div class="mb-3">
                    <label for="selectType1" class="form-label">Tipo de Documento*</label>
                    <select name="documentTypes" id="selectType1" class="form-select">
                        <option value="">Selecione o tipo</option>
                        @foreach ($documentTypes as $documentType)
                            <option value="{{ $documentType->id }}">{{ $documentType->name }}</option>
                        @endforeach
                    </select>
                    @error('documentTypes')
                        <div class="error">> {{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="selectEmployee1" class="form-label">Colaborador*</label>
                    <select name="employees" id="selectEmployee1" class="form-select">
                        <option value="">Selecione o colaborador</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}" {{ $employee->id == $id ? 'selected' : '' }}>
                                {{ $employee->name }}</option>
                        @endforeach
                    </select>
                    @error('employees')
                        <div class="error">> {{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Enviar arquivo</button> <button type="button"
                    onclick="history.back()" class="btn btn-secondary">Cancelar</button>
                @error('noStore')
                    <div class="error">> {{ $message }}</div>
                @enderror
            </form>
            <br />
            <p>Formato esperado de arquivos: pdf,jpeg,png,jpg</p><br />
            <br />
        </main>
    </section>
@endsection
