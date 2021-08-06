@extends('layouts.basic')

@section('title', 'Importar Documento de Vínculo')

@section('content')
    <section>
        <h2>Importar Documento de Vínculo</h2>
    </section>
    <section id="pageContent">
        <main role="main">
            <form action={{ route('bonds.document.store') }} method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="inputFile1" class="form-label">Selecione o arquivo</label>
                    <input class="form-control" type="file" name="file" id="inputFile1" accept="image/*,.pdf">
                </div>
                <div class="mb-3">
                    <label for="selectType1" class="form-label">Tipo de Documento*</label>
                <select name="documentTypes" id="selectType1" class="form-select">
                    <option value="">Selecione o tipo</option>
                    @foreach ($documentTypes as $documentType)
                        <option value="{{ $documentType->id }}" {{-- {{($documentType->id == $document->document_type_id) ? 'selected' : ''}} --}}>{{ $documentType->name }}</option>
                    @endforeach
                </select>
                @error('documentTypes')
                    <div class="error">> {{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="selectBond1" class="form-label">Vínculo*</label>
                <select name="bonds" id="selectBond1" class="form-select">
                    <option value="">Selecione o tipo</option>
                    @foreach ($bonds as $bond)
                        <option value="{{ $bond->id }}" {{-- {{($documentType->id == $document->document_type_id) ? 'selected' : ''}} --}}>{{ $bond->employee->name . '-' . $bond->role->name . '-' . $bond->course->name }}</option>
                    @endforeach
                </select>
                @error('bonds')
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
            <p>Formato esperado de arquivos: pdf, jpeg, png, jpg</p><br />
            <br />
        </main>
    </section>
@endsection
