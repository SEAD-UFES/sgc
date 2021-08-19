<h4>> Dados Pessoais</h4>
<div class="mb-2 row">
    <div class="col-sm-4 col-lg-3">Nome:</div>
    <div class="col-sm-8 col-lg-9">{{ $bond->employee->name ?? '-' }}</div>
</div>
<div class="mb-2 row">
    <div class="col-sm-4 col-lg-3">Cidade:</div>
    <div class="col-sm-8 col-lg-9">{{ $bond->employee->address_city ?? '-' }}</div>
</div>
<div class="mb-2 row">
    <div class="col-sm-4 col-lg-3">Email:</div>
    <div class="col-sm-8 col-lg-9">{{ $bond->employee->email ?? '-' }}</div>
</div>
<h4>> Dados do vínculo</h4>
<div class="mb-2 row">
    <div class="col-sm-4 col-lg-3">Atribuição:</div>
    <div class="col-sm-8 col-lg-9">{{ $bond->role->name ?? '-' }}</div>
</div>
<div class="mb-2 row">
    <div class="col-sm-4 col-lg-3">Curso:</div>
    <div class="col-sm-8 col-lg-9">{{ $bond->course->name ?? '-' }}</div>
</div>
<div class="mb-2 row">
    <div class="col-sm-4 col-lg-3">Polo:</div>
    <div class="col-sm-8 col-lg-9">{{ $bond->pole->name ?? '-' }}</div>
</div>
<div class="mb-2 row">
    <div class="col-sm-4 col-lg-3">Criado em:</div>
    <div class="col-sm-8 col-lg-9">{{ ($bond->created_at != null) ? \Carbon\Carbon::parse($bond->created_at)->isoFormat('DD/MM/Y hh:mm') : '-' }}</div>
</div>
<div class="mb-2 row">
    <div class="col-sm-4 col-lg-3">Início:</div>
    <div class="col-sm-8 col-lg-9">{{ ($bond->begin != null) ? \Carbon\Carbon::parse($bond->begin)->isoFormat('DD/MM/Y') : '-' }}</div>
</div>
<div class="mb-2 row">
    <div class="col-sm-4 col-lg-3">Fim:</div>
    <div class="col-sm-8 col-lg-9">{{ ($bond->end != null) ? \Carbon\Carbon::parse($bond->end)->isoFormat('DD/MM/Y') : '-' }}</div>
</div>
<div class="mb-2 row">
    <div class="col-sm-4 col-lg-3">Voluntário:</div>
    <div class="col-sm-8 col-lg-9">{{ $bond->volunteer === 1 ? 'Sim' : 'Não' }}</div>
</div>
<div class="mb-2 row">
    <div class="col-sm-4 col-lg-3">Encerrado em:</div>
    <div class="col-sm-8 col-lg-9">{{ ($bond->terminated_at != null) ? \Carbon\Carbon::parse($bond->terminated_at)->isoFormat('DD/MM/Y hh:mm') : '-' }}</div>
</div>
<div class="mb-2 row">
    <div class="col-sm-4 col-lg-3">Impedido:</div>
    <div class="col-sm-8 col-lg-9">{{ $bond->impediment === 1 ? 'Sim' : 'Não' }}</div>
</div>
<div class="mb-2 row">
    <div class="col-sm-4 col-lg-3">Descrição do Impedimento:</div>
    <div class="col-sm-8 col-lg-9">{{ $bond->impediment_description ?? '-' }}</div>
</div>
<div class="mb-2 row">
    <div class="col-sm-4 col-lg-3">Revisado em:</div>
    <div class="col-sm-8 col-lg-9">{{ ($bond->uaba_checked_at != null) ? \Carbon\Carbon::parse($bond->uaba_checked_at)->isoFormat('DD/MM/Y hh:mm') : '-' }}</div>
</div>