@extends('layouts.basic')

@section('title', 'Importar Documentos de Colaborador')

@section('content')
    <section>
        <h2>Importar Documentos de Colaborador</h2>
    </section>
    <section id="pageContent">
        <main role="main">
            <form action={{ route('employees.document.mass.import') }} method="POST" enctype="multipart/form-data">
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
                    <select name="employees" id="selectEmployee1" class="form-select">
                        <option value="">Selecione o colaborador</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}" {{ $employee->id == $id ? 'selected' : '' }}>
                                {{ $employee->name . ' - ' . $employee->cpf }}</option>
                        @endforeach
                    </select>
                    @error('employees')
                        <div class="error">> {{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Enviar arquivos</button> <button type="button"
                    onclick="history.back()" class="btn btn-secondary">Cancelar</button>
                @error('noStore')
                    <div class="error">> {{ $message }}</div>
                @enderror
            </form>
            <br />
            <p>Formato esperado de arquivos: pdf, jpeg, png, jpg</p><br />
            <br />
        </main>
    </section>
@endsection
