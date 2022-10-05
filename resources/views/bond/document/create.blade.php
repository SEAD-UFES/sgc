@extends('layouts.basic')

@section('title', 'Importar Documento de Vínculo')

@section('headerScripts')
    <script src="{{ asset('js/enable_searchable_select.js') }}"></script>
@endsection

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Colaboradores</li>
            <li class="breadcrumb-item active" aria-current="page">Importar Documento de Vínculo</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-xxl-8">
                    @include('_components.alerts')
                    <form action={{ route('bonds_documents.store') }} method="POST" enctype="multipart/form-data">
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
                                    <option value="{{ $documentType->id }}" {{-- {{($documentType->id == $document->document_type_id) ? 'selected' : ''}} --}}>{{ $documentType->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('document_type_id')
                                <div class="text-danger">> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="selectBond1" class="form-label">Vínculo*</label>
                            <select name="bond_id" id="selectBond1" class="form-select searchable-select">
                                <option value="">Selecione o vínculo</option>
                                @foreach ($bonds as $bond)
                                    <option value="{{ $bond->id }}" {{-- {{($documentType->id == $document->document_type_id) ? 'selected' : ''}} --}}>
                                        {{ $bond->employee->name . ' - ' . $bond->role->name . ' - ' . $bond->course->name }}</option>
                                @endforeach
                            </select>
                            @error('bond_id')
                                <div class="text-danger">> {{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar arquivo</button>
                        <a href="{{ route('bonds_documents.index') }}" class="btn btn-secondary">Cancelar</a>
                        @can('bondDocument-store')
                            <a href="{{ route('bonds_documents.create_many') }}" class="btn btn-warning">Desejo importar vários documentos de vínculo</a>
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
