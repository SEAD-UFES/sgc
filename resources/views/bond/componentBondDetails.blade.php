
@component('employee.componentBasicEmployeeData', ['employee'=>$bond->employee])@endcomponent

<div class="card mb-3">
    <div class="card-header collapsed" data-bs-toggle="collapse" href="#bondDataContent" role="button" aria-expanded="false" aria-controls="bondDataContent">
        <h4 class='mb-0'>Dados do Vínculo</h4>
    </div>

    <div class="collapse" id="bondDataContent" >
        <div class="card-body">
            <div class="mb-2 row">
                <div class="col-sm-4 col-lg-3"><strong>Função:</strong></div>
                <div class="col-sm-8 col-lg-9">{{ $bond->role->name ?? '-' }}</div>
            </div>
            <div class="mb-2 row">
                <div class="col-sm-4 col-lg-3"><strong>Curso:</strong></div>
                <div class="col-sm-8 col-lg-9">{{ $bond->course->name ?? '-' }}</div>
            </div>
            <div class="mb-2 row">
                <div class="col-sm-4 col-lg-3"><strong>Polo:</strong></div>
                <div class="col-sm-8 col-lg-9">{{ $bond->pole->name ?? '-' }}</div>
            </div>
            <div class="mb-2 row">
                <div class="col-sm-4 col-lg-3"><strong>Criado em:</strong></div>
                <div class="col-sm-8 col-lg-9">{{ ($bond->created_at != null) ? \Carbon\Carbon::parse($bond->created_at)->isoFormat('DD/MM/Y HH:mm') : '-' }}</div>
            </div>
            <div class="mb-2 row">
                <div class="col-sm-4 col-lg-3"><strong>Início:</strong></div>
                <div class="col-sm-8 col-lg-9">{{ ($bond->begin != null) ? \Carbon\Carbon::parse($bond->begin)->isoFormat('DD/MM/Y') : '-' }}</div>
            </div>
            <div class="mb-2 row">
                <div class="col-sm-4 col-lg-3"><strong>Fim:</strong></div>
                <div class="col-sm-8 col-lg-9">{{ ($bond->end != null) ? \Carbon\Carbon::parse($bond->end)->isoFormat('DD/MM/Y') : '-' }}</div>
            </div>
            <div class="mb-2 row">
                <div class="col-sm-4 col-lg-3"><strong>Voluntário:</strong></div>
                <div class="col-sm-8 col-lg-9">{{ $bond->volunteer === 1 ? 'Sim' : 'Não' }}</div>
            </div>
            <div class="mb-2 row">
                <div class="col-sm-4 col-lg-3"><strong>Encerrado em:</strong></div>
                <div class="col-sm-8 col-lg-9">{{ ($bond->terminated_at != null) ? \Carbon\Carbon::parse($bond->terminated_at)->isoFormat('DD/MM/Y HH:mm') : '-' }}</div>
            </div>

            <div class="">
                @can('bond-update', $bond)
                    <a href="{{ route('bonds.edit', $bond->id) }}" data-bs-toggle="tooltip" title="Editar vínculo" class="btn btn-primary btn-sm">
                        <i class="bi-pencil-fill"></i> Editar vínculo
                    </a>&nbsp;
                @endcan
            </div>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header collapsed" data-bs-toggle="collapse" href="#bondQualificationDataContent" role="button" aria-expanded="false" aria-controls="bondQualificationDataContent">
        <h4 class='mb-0'>Qualificação</h4>
    </div>

    <div class="collapse" id="bondQualificationDataContent" >
        <div class="card-body">
            <div class="mb-2 row">
                <div class="col-sm-4 col-lg-3"><strong>Área do último Curso Superior:</strong></div>
                <div class="col-sm-8 col-lg-9">{{ $bond->qualification->knowledge_area->value ?? '-' }}</div>
            </div>
            <div class="mb-2 row">
                <div class="col-sm-4 col-lg-3"><strong>Último curso de titulação:</strong></div>
                <div class="col-sm-8 col-lg-9">{{ $bond->qualification->course_name ?? '-' }}</div>
            </div>
            <div class="mb-2 row">
                <div class="col-sm-4 col-lg-3"><strong>Nome da Instituição de Titulação:</strong></div>
                <div class="col-sm-8 col-lg-9">{{ $bond->qualification->institution_name ?? '-' }}</div>
            </div>

            <div class="">
                @can('bond-update', $bond)
                    <a href="{{ route('bonds.edit', $bond->id) }}" data-bs-toggle="tooltip" title="Editar vínculo" class="btn btn-primary btn-sm">
                        <i class="bi-pencil-fill"></i> Editar Qualificação
                    </a>&nbsp;
                @endcan
            </div>
        </div>
    </div>
</div>
