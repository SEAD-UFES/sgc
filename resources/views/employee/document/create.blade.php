@extends('layouts.basic')

@section('title', 'Importar Documento de Colaborador')

@section('content')
    <section>
        <strong>Importar Documento de Colaborador</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <form action={{ route('employees.document.store') }} method="POST" enctype="multipart/form-data">
                @csrf
                Selecione o arquivo: <input type="file" name="file">
                <br /><br />
                Tipo de Documento*: <select name="documentTypes">
                    <option value="">Selecione o tipo</option>
                    @foreach ($documentTypes as $documentType)
                        <option value="{{ $documentType->id }}" {{-- {{($documentType->id == $document->document_type_id) ? 'selected' : ''}} --}}>{{ $documentType->name }}</option>
                    @endforeach
                </select>
                @error('documentTypes')
                    <div class="error">> {{ $message }}</div>
                @enderror
                <br /><br />
                Colaborador*: <select name="employees">
                    <option value="">Selecione o colaborador</option>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}" {{ ($employee->id == $id) ? 'selected' : ''}}>{{ $employee->name }}</option>
                    @endforeach
                </select>
                @error('employees')
                    <div class="error">> {{ $message }}</div>
                @enderror
                <br /><br />
                <button type="submit">Enviar arquivo</button> <button type="button"
                    onclick="history.back()">Cancelar</button>
                @error('noStore')
                    <div class="error">> {{ $message }}</div>
                @enderror
                <br /><br /><br />
            </form>
            <br />
            <p>Formato esperado de arquivos: pdf,jpeg,png,jpg</p><br />
            <br />
        </main>
    </section>
@endsection
