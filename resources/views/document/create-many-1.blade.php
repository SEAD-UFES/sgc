@extends('layouts.basic')

@section('title', 'Importar Documentos de Vínculo')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Colaboradores</li>
            <li class="breadcrumb-item active" aria-current="page">Importar Documentos de Vínculo</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-xxl-8">
                    @include('_components.alerts')
                    <form action={{ route('documents.store_many_1') }} method="POST" enctype="multipart/form-data">
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
                            <label for="selectBond1" class="form-label">Vínculo*</label>
                            <select name="bond_id" id="selectBond1" class="form-select">
                                <option value="">Selecione o vínculo</option>
                                @foreach ($bonds as $bond)
                                    <option value="{{ $bond->id }}" {{ $bond->id == $id ? 'selected' : '' }}>
                                        {{ $bond->employee->name . ' - ' . $bond->role->name . ' - ' . $bond->course->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('bond_id')
                                <div class="text-danger">> {{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Enviar arquivos</button>
                        <a href="{{ route('documents.index') }}" class="btn btn-secondary">Cancelar</a>

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
