<h3>Vínculo</h3><br />
<h4>> Dados Pessoais</h4>
<div class="mb-1 row">
    <label for="staticEmail" class="col-sm-2 col-form-label">Nome</label>
    <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="{{ $bond->employee->name ?? '-' }}">
    </div>
</div>
<div class="mb-1 row">
    <label for="staticEmail" class="col-sm-2 col-form-label">Cidade</label>
    <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="{{ $bond->employee->address_city ?? '-' }}">
    </div>
</div>
<div class="mb-1 row">
    <label for="staticEmail" class="col-sm-2 col-form-label">Email</label>
    <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="{{ $bond->employee->email ?? '-' }}">
    </div>
</div>
<h4>> Dados do vínculo</h4><br />
<div class="mb-1 row">
    <label for="staticEmail" class="col-sm-2 col-form-label">Atribuição</label>
    <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="{{ $bond->role->name ?? '-' }}">
    </div>
</div>
<div class="mb-1 row">
    <label for="staticEmail" class="col-sm-2 col-form-label">Curso</label>
    <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="{{ $bond->course->name ?? '-' }}">
    </div>
</div>
<div class="mb-1 row">
    <label for="staticEmail" class="col-sm-2 col-form-label">Polo</label>
    <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="{{ $bond->pole->name ?? '-' }}">
    </div>
</div>
<div class="mb-1 row">
    <label for="staticEmail" class="col-sm-2 col-form-label">Criado em</label>
    <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="{{ $bond->created_at ?? '-' }}">
    </div>
</div>
<div class="mb-1 row">
    <label for="staticEmail" class="col-sm-2 col-form-label">Início</label>
    <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="{{ $bond->begin ?? '-' }}">
    </div>
</div>
<div class="mb-1 row">
    <label for="staticEmail" class="col-sm-2 col-form-label">Fim</label>
    <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="{{ $bond->end ?? '-' }}">
    </div>
</div>
<div class="mb-1 row">
    <label for="staticEmail" class="col-sm-2 col-form-label">Voluntário</label>
    <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="{{ $bond->volunteer ?? '' }}">
    </div>
</div>
<div class="mb-1 row">
    <label for="staticEmail" class="col-sm-2 col-form-label">Encerrado em</label>
    <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="{{ $bond->terminated_on ?? '-' }}">
    </div>
</div>
<div class="mb-1 row">
    <label for="staticEmail" class="col-sm-2 col-form-label">Impedido</label>
    <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="{{ $bond->impediment ?? '' }}">
    </div>
</div>
<div class="mb-1 row">
    <label for="staticEmail" class="col-sm-2 col-form-label">Descrição do Impedimento</label>
    <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="{{ $bond->impediment_description ?? '-' }}">
    </div>
</div>
<div class="mb-1 row">
    <label for="staticEmail" class="col-sm-2 col-form-label">Revisado em</label>
    <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="{{ $bond->uaba_checked_on ?? '-' }}">
    </div>
</div>