@extends('layouts.basic')

@section('title', 'Exibir Vínculo')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Colaboradores</li>
            <li class="breadcrumb-item"><a href="{{ route('bonds.index') }}">Listar Vínculos</a></li>
            <li class="breadcrumb-item active" aria-current="page">Exibir:
                [{{ $bond->employee->name . '-' . $bond->role->name . '-' . $bond->course?->name . '-' . $bond->pole?->name }}]
            </li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-xxl-8">

                    @include('_components.alerts')

                    <h4>Vínculo: {{ $bond->employee->name . ' - ' . $bond->role->name . ' - ' . $bond->course?->name }}</h4>

                    @component('bond.componentBondDetails', compact('bond'))@endcomponent

                    <div class="card mb-3">
                        <div class="card-header collapsed" data-bs-toggle="collapse" href="#bondDocumentListContent" role="button"
                            aria-expanded="false" aria-controls="bondDocumentListContent">
                            <h4 class='mb-0'>Documentos do Vínculo</h4>
                        </div>
                        <div class="collapse" id="bondDocumentListContent">
                            <div class="card-body">
                                @if ($bond->documents->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <th>Última atualização</th>
                                                <th>Tipo do arquivo</th>
                                                <th>Nome do arquivo</th>
                                            </thead>
                                            <tbody>
                                                @foreach ($bond->documents as $document)
                                                    <tr>
                                                        <td>
                                                            {{ $document->updated_at ? \Carbon\Carbon::parse($document->updated_at)->isoFormat('DD/MM/Y HH:mm') : '-' }}
                                                        </td>
                                                        <td>{{ $document->documentType->name }}</td>
                                                        <td>
                                                            <a href="{{ route('documents.show', ['id' => $document->id, 'htmlTitle' => $document->file_name]) }}"
                                                                target="_blank">{{ $document->file_name }}</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <a href="{{ route('documents.export', $bond) }}"
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
                        $rightsTypeId = App\Models\DocumentType::where('name', 'Termo de cessão de direitos')->first()->id;
                        $hasRights = $bond->documents->where('document_type_id', $rightsTypeId)->count() > 0;
                    @endphp

                    <div class="card {{ is_null($bond->last_open_impediment_date) ? 'border-success' : 'border-warning' }}  mb-3">
                        <div class="card-header {{ is_null($bond->last_open_impediment_date) ? 'bg-success' : 'bg-warning' }}"
                            data-bs-toggle="collapse" href="#bondReviewContent" role="button" aria-expanded="true"
                            aria-controls="bondReviewContent">
                            <h4 class='mb-0'>Revisão do Vínculo</h4>
                        </div>

                        <div class="collapse show" id="bondReviewContent">
                            <div class="card-body">

                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Última revisão:</strong></div>
                                    <div class="col-sm-8 col-lg-9">
                                        {{ $bond->impediments != null ? \Carbon\Carbon::parse($bond->last_open_impediment_date)->isoFormat('DD/MM/YYYY HH:mm:ss') : '-' }}
                                    </div>
                                </div>

                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3">
                                        <strong>Status do vínculo:</strong>
                                    </div>
                                    <div class="col-sm-8 col-lg-9">
                                        {{ is_null($bond->last_open_impediment_date) ? 'Sem pendências' : 'Impedido' }}
                                    </div>
                                    @if (!$hasRights)
                                        <div class="text-danger"><i class="bi bi-exclamation-triangle-fill"></i> <strong>Atenção:</strong> Sem o documento "Termo de cessão de direitos" o vínculo permanecerá impedido.</div>
                                    @endif
                                </div>

                                @if ($bond->impediments->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <th>Impedimento</th>
                                                <th>Impedido por</th>
                                                <th>Impedido em</th>
                                                @can('bond-review')
                                                    <th>Ações</th>
                                                @endcan
                                            </thead>
                                            <tbody>
                                                @foreach ($bond->impediments as $impediment)
                                                    <tr>
                                                        <td data-bs-html="true" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="bottom" 
                                                        data-bs-content="
                                                            <strong>Fechado por: </strong>{{ $impediment->closedBy?->login ?? '-'}} |
                                                            <strong>Fechado em: </strong>{{ (!is_null($impediment->closed_at) ? (\Carbon\Carbon::parse($impediment->closed_at)->isoFormat('DD/MM/Y HH:mm')) : '-') }}
                                                        ">
                                                            @if ($impediment->closed_at == null)
                                                                <i class="bi bi-exclamation-triangle-fill text-warning"></i> 
                                                            @else
                                                                <i class="bi bi-check-circle-fill text-success"></i> <del class="text-muted">
                                                            @endif
                                                            {{ $impediment->description }}
                                                            @if ($impediment->closed_at != null)
                                                                </del>
                                                            @endif
                                                        </td>
                                                        <td>{{ $impediment->reviewer->login }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($impediment->created_at)->isoFormat('DD/MM/Y HH:mm') }}</td>
                                                        @can('bond-review')
                                                            <td>
                                                                @if ($impediment->closed_at == null && $impediment->description !== '[SGC: Documento "Termo de cessão de direitos" ainda não importado]')
                                                                    <form name="{{ 'formImpedimentClose' . $impediment->id }}"
                                                                        action="{{ route('impediments.update', $impediment->id) }}" method="POST">
                                                                        @method('PATCH')
                                                                        @csrf
                                                                        <button type="button" data-bs-toggle="tooltip" title="Encerrar impedimento" 
                                                                            onclick="{{ 'if(confirm(\'Tem certeza que deseja encerrar esse impedimento?\')) document.forms[\'formImpedimentClose' . $impediment->id . '\'].submit();' }}"
                                                                            class="btn btn-success btn-sm">
                                                                            <i class="bi bi-check-lg"></i>
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            </td>
                                                        @endcan
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif

                                @can('bond-requestReview')
                                    <a class="btn btn-primary btn-sm mt-1"
                                        href="{{ route('bonds.request_review', $bond->id) }}">Enviar solicitação de revisão de
                                        vínculo</a>
                                @endcan

                                @can('bond-review')
                                    <a class="btn btn-warning btn-sm mt-1" data-bs-toggle="collapse"
                                        href="#bondImpedeFormContainer" role="button" aria-expanded="false"
                                        aria-controls="bondImpedeFormContainer">Exibir formulário de criação de Impedimento <i class="bi bi-chevron-double-down"></i></a>
                                    <div class="collapse" id="bondImpedeFormContainer">
                                        <hr>
                                        <form name="formNewImpediment" action="{{ route('impediments.store') }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="impedimentDescription" class="form-label">Motivo do
                                                    impedimento:</label><br />
                                                <textarea class="form-control" id="impedimentDescription"
                                                    name="impediment_description"
                                                    rows="4" placeholder="Motivo do Impedimento"></textarea>
                                            </div>
                                            <input type="hidden" name="bond_id" value="{{ $bond->id }}">
                                            <input type="submit" value="Criar Impedimento" class="btn btn-primary btn-sm mt-1">
                                        </form>
                                    </div>
                                @endcan

                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header collapsed" data-bs-toggle="collapse" href="#institutionDetails" role="button"
                            aria-expanded="false" aria-controls="institutionDetails">
                            <h4 class='mb-0'>Detalhes Institucionais</h4>
                        </div>
                        <div class="collapse" id="institutionDetails">
                            <div class="card-body">
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Login Institucional:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ $bond->employee->institutionalDetail->login ?? '-' }}</div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Email Institucional:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ $bond->employee->institutionalDetail->email ?? '-' }}</div>
                                </div>
                                <a href="{{ route('bonds.send_new_employee_emails', $bond) }}" class="btn btn-primary btn-sm mt-1{{($bond->employee->institutionalDetail == null) ? ' disabled' : '' }}">
                                    Enviar comunicado sobre a criação do Login de Acesso
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header collapsed" data-bs-toggle="collapse" href="#activityDetails" role="button"
                            aria-expanded="false" aria-controls="activityDetails">
                            <h4 class='mb-0'>Informações de Cadastro</h4>
                        </div>
                        <div class="collapse" id="activityDetails">
                            <div class="card-body">
                                <div class="mb-2 row">
                                    <div class="col-sm-12 col-lg-12"><h5>Cadastrado</h5></div>
                                    <div class="col-sm-6 col-lg-6"><strong>Por:</strong> {{ $bond->createdBy?->login ?? '-' }}</div>
                                    <div class="col-sm-6 col-lg-6"><strong>Em:</strong> {{ (is_null($bond->createdOn)) ? '-' : \Carbon\Carbon::parse($bond->createdOn)->isoFormat('DD/MM/Y HH:mm') }}</div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-sm-12 col-lg-12"><h5>Atualizado por último</h5></div>
                                    <div class="col-sm-6 col-lg-6"><strong>Por:</strong> {{ $bond->updatedBy?->login ?? '-' }}</div>
                                    <div class="col-sm-6 col-lg-6"><strong>Em:</strong> {{ (is_null($bond->updatedOn)) ? '-' : \Carbon\Carbon::parse($bond->updatedOn)->isoFormat('DD/MM/Y HH:mm') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('bonds.index') }}" class="btn btn-secondary">Lista de Vínculos</a>
                    <br/><br/>
                </div>
            </div>
        </main>
    </section>
@endsection

@section('scripts')
    <script src="{{ asset('js/enable_tooltip_popover.js') }}"></script>
@endsection
