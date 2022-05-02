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

                    @include('_components.alerts')

                    @component('bond.componentBondDetails', compact('bond'))@endcomponent

                    <div class="card mb-3">
                        <div class="card-header" data-bs-toggle="collapse" href="#bondDocumentListContent" role="button"
                            aria-expanded="true" aria-controls="bondDocumentListContent">
                            <h4 class='mb-0'>Documentos do vínculo</h4>
                        </div>
                        <div class="collapse show" id="bondDocumentListContent">
                            <div class="card-body">
                                @if ($documents->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <th>Última atualização</th>
                                                <th>Tipo do arquivo</th>
                                                <th>Nome do arquivo</th>
                                            </thead>
                                            <tbody>
                                                @foreach ($documents as $document)
                                                    <tr>
                                                        <td>
                                                            {{ $document->updated_at ? \Carbon\Carbon::parse($document->updated_at)->isoFormat('DD/MM/Y hh:mm') : '-' }}
                                                        </td>
                                                        <td>{{ $document->documentType->name }}</td>
                                                        <td>
                                                            <a href="{{ route('documents.show', ['id' => $document->id, 'htmlTitle' => $document->original_name]) }}"
                                                                target="_blank">{{ $document->original_name }}</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <a href="{{ route('bondsDocuments.export', $bond) }}"
                                        class="btn btn-primary btn-sm mt-1">
                                        &nbsp;&#8627; Fazer o download de todos os documentos do vínculo (zip)
                                    </a>
                                @else
                                    <p class="mb-0">O vínculo não possui documentos cadastrados.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    @php
                        $rightsTypeId = App\Models\DocumentType::where('name', 'Ficha de Inscrição - Termos e Licença')->first()->id;
                        $hasRights = $documents->where('document_type_id', $rightsTypeId)->count() > 0;
                    @endphp

                    <div class="card {{ $bond->impediment == 0 ? 'border-success' : 'border-warning' }}  mb-3">
                        <div class="card-header {{ $bond->impediment == 0 ? 'bg-success' : 'bg-warning' }}"
                            data-bs-toggle="collapse" href="#bondReviewContent" role="button" aria-expanded="true"
                            aria-controls="bondReviewContent">
                            <h4 class='mb-0'>Revisão do vínculo</h4>
                        </div>

                        <div class="collapse show" id="bondReviewContent">
                            <div class="card-body">

                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Última revisão:</strong></div>
                                    <div class="col-sm-8 col-lg-9">
                                        {{ $bond->uaba_checked_at != null ? \Carbon\Carbon::parse($bond->uaba_checked_at)->isoFormat('DD/MM/Y hh:mm') : '-' }}
                                    </div>
                                </div>

                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3">
                                        <strong>Status do vínculo:</strong>
                                    </div>
                                    <div class="col-sm-8 col-lg-9">
                                        {{ $bond->impediment == 0 ? 'Sem pendências' : 'Impedido' }}
                                        {{ $bond->impediment == 0 && !$hasRights ? ' - OBS: Sem o documento "Ficha de Inscrição - Termos e Licença" o vínculo permanecerá impedido.' : '' }}
                                    </div>
                                </div>

                                @if ($bond->impediment == '1')
                                    <div class="mb-2 row">
                                        <div class="col-sm-4 col-lg-3">
                                            <strong>Descrição do impedimento:</strong>
                                        </div>
                                        <div class="col-sm-8 col-lg-9">
                                            {{ $bond->impediment == '1' ? $bond->impediment_description : '-' }}
                                            @if (!$hasRights && $bond->impediment_description) <br> @endif
                                            {{ !$hasRights ? 'OBS: Sem o documento "Ficha de Inscrição - Termos e Licença" o vínculo permanecerá impedido.' : '' }}
                                        </div>
                                    </div>
                                @endif

                                @can('bond-requestReview')
                                    <a class="btn btn-primary btn-sm mt-1"
                                        href="{{ route('bonds.requestReview', $bond->id) }}">Enviar solicitação de revisão de
                                        vínculo</a>
                                @endcan

                                @can('bond-review')
                                    <a class="btn btn-primary btn-sm mt-1" data-bs-toggle="collapse"
                                        href="#bondReviewFormContainer" role="button" aria-expanded="false"
                                        aria-controls="bondReviewFormContainer">Exibir formulário de revisão do vínculo</a>
                                @endcan

                                @can('bond-review')
                                    <div class="collapse" id="bondReviewFormContainer">
                                        <hr>
                                        <form name="{{ 'formReview' . $bond->id }}"
                                            action="{{ route('bonds.review', $bond) }}" method="POST">
                                            @csrf
                                            <label class="form-label">Impedido:</label>
                                            <div class="mb-3 form-check">
                                                <input class="form-check-input" type="radio" id="sim" name="impediment"
                                                    value="1" {{ $bond->impediment == '1' ? 'checked' : '' }}
                                                    onclick="document.getElementById('impedimentDescription').disabled=false;">
                                                <label for="sim" class="form-check-label">
                                                    Sim
                                                </label>
                                                <br>

                                                <input class="form-check-input" type="radio" id="nao" name="impediment"
                                                    value="0" {{ $bond->impediment == '0' ? 'checked' : '' }}
                                                    onclick="document.getElementById('impedimentDescription').disabled=true;">
                                                <label for="nao" class="form-check-label">
                                                    Não (Sem o documento "Ficha de Inscrição - Termos e Licença" o vínculo
                                                    permanecerá impedido.)
                                                </label>
                                            </div>
                                            <div class="mb-3">
                                                <label for="impedimentDescription" class="form-label">Motivo do
                                                    impedimento:</label><br />
                                                <textarea class="form-control" id="impedimentDescription"
                                                    name="impediment_description"
                                                    rows="4">{{ $bond->impediment_description ?? '' }}</textarea>
                                                <script>
                                                    if ({{ $bond->impediment }} == '0')
                                                        document.getElementById('impedimentDescription').disabled = true;
                                                </script>
                                            </div>
                                            <input type="submit" value="Revisar" class="btn btn-primary btn-sm mt-1">
                                        </form>
                                    </div>
                                @endcan

                            </div>
                        </div>
                    </div>

                    <button type="button mb-1" onclick="history.back()" class="btn btn-secondary">Voltar</button>
                </div>
            </div>
        </main>
    </section>
@endsection
