@extends('layouts.basic')

@section('title', 'Exibir Vínculo')

@section('content')
<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
    <ol class="breadcrumb border-top border-bottom bg-light">
        <li class="breadcrumb-item">Colaboradores</li>
        <li class="breadcrumb-item"><a href="{{ route('bonds.index') }}">Listar Vínculos</a></li>
        <li class="breadcrumb-item active" aria-current="page">Exibir:
            [{{ $bond->employee->name . '-' . $bond->role->name . '-' . $bond->course->name . '-' . $bond->pole->name }}]
        </li>
    </ol>
</nav>
<section id="pageContent">
    <main role="main">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-xxl-8">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <span style="color: green; font-weight: bold">{{ $message }}</span>
                    </div>
                @endif
                @component('bond.componentBondDetails', compact('bond'))@endcomponent
                <br />
                <h4>> Documentos</h4>
                @component('bond.document.componentList', compact('documents'))@endcomponent
                <a href="{{ route('bonds.document.massdownload', $bond) }}" 
                    class="btn btn-primary btn-sm">&nbsp;&#8627; Fazer o download de todos os documentos do
                    vínculo (zip)</a>
                <br /><br /><br />

                @php
                    $hasRights = $documents->where('document_type_id', App\Models\DocumentType::where('name', 'Ficha de Inscrição - Termos e Licença')->first()->id)->count() > 0;
                @endphp
                @canany(['isAdm', 'isDir', 'isAss'])
                    <fieldset class="bg-warning px-2 py-2">
                        <h4>> Revisão</h4>
                        <form name="{{ 'formReview' . $bond->id }}" action="{{ route('bonds.review', $bond) }}" method="POST">
                            @csrf
                            <label class="form-label">Impedido:</label><br />
                            <div class="mb-3 form-check">
                                <input class="form-check-input" type="radio" id="sim" name="impediment" value="1"
                                    {{ $bond->impediment == '1' ? 'checked' : '' }}
                                    onclick="document.getElementById('impedimentDescription').disabled=false;">
                                <label for="sim" class="form-check-label">Sim</label><br>
                                <input class="form-check-input" type="radio" id="nao" name="impediment" value="0"
                                    {{ $bond->impediment == '0' ? 'checked' : '' }}
                                    onclick="document.getElementById('impedimentDescription').disabled=true;"
                                    {{ !$hasRights ? 'disabled' : '' }}>
                                <label for="nao" class="form-check-label">Não*</label><br>
                                <p>* Para a aprovação de um vínculo é necessário ter o documento 'Ficha de Inscrição - Termos e Licença'</p>
                            </div>
                            <div class="mb-3">
                                <label for="impedimentDescription" class="form-label">Motivo do impedimento:</label><br />
                                <textarea class="form-control" id="impedimentDescription" name="impedimentDescription"
                                    rows="4">{{ $bond->impediment_description ?? '' }}</textarea>
                                <script>
                                    if ({{ $bond->impediment }} == '0')
                                        document.getElementById('impedimentDescription').disabled = true;
                                </script>
                            </div>
                            <input type="submit" value="Revisar" class="btn btn-primary my-2 mx-1">
                        </form>
                    </fieldset><br />
                @endcanany

                @canany(['isAdm', 'isDir', 'isSec', 'isCor'])
                    @if ($bond->impediment == '1')
                        <fieldset class="bg-warning px-2 py-2">
                            <h4>> Nova Revisão</h4>
                            <a href="{{ route('bonds.requestReview', $bond->id) }}" class="btn btn-primary my-2 mx-1">Solicitar
                                nova revisão</a>
                        </fieldset><br />
                    @endif
                @endcanany

                <button type="button" onclick="history.back()" class="btn btn-secondary">Voltar</button>
                <br /><br />
            </div>
        </div>
    </main>
</section>
@endsection
