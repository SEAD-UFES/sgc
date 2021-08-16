<h3>Formulário UAB</h3>
<div class="card">
    <div class="card-header" data-bs-toggle="collapse" href="#collapsePersonal" role="button" aria-expanded="true" aria-controls="collapsePersonal">> Dados Pessoais</div>
    <div class="collapse show card-body" id="collapsePersonal">
        <div class="mb-1 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Nome:</label>
            <div class="col-sm-10">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail"
                    value="{{ $employee->name ?? '-' }}">
            </div>
        </div>
        <div class="mb-1 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">CPF:</label>
            <div class="col-sm-10">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail"
                    value="{{ $employee->cpf ?? '-' }}">
            </div>
        </div>
        <div class="mb-1 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Profissão:</label>
            <div class="col-sm-10">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail"
                    value="{{ $employee->job ?? '-' }}">
            </div>
        </div>
        <div class="mb-1 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Gênero:</label>
            <div class="col-sm-10">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail"
                    value="{{ $employee->gender->name ?? '-' }}">
            </div>
        </div>
        <div class="mb-1 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Data de Nascimento:</label>
            <div class="col-sm-10">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail"
                    value="{{ $employee->birthday != null ? \Carbon\Carbon::parse($employee->birthday)->isoFormat('DD/MM/Y') : '-' }}">
            </div>
        </div>
        <div class="mb-1 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">UF Nascimento:</label>
            <div class="col-sm-10">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail"
                    value="{{ $employee->birthState->uf ?? '-' }}">
            </div>
        </div>
        <div class="mb-1 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Cidade de Nascimento:</label>
            <div class="col-sm-10">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail"
                    value="{{ $employee->birth_city ?? '-' }}">
            </div>
        </div>
        <div class="mb-1 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Número do Documento:</label>
            <div class="col-sm-10">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail"
                    value="{{ $employee->id_number ?? '-' }}">
            </div>
        </div>
        <div class="mb-1 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Tipo de Documento:</label>
            <div class="col-sm-10">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail"
                    value="{{ $employee->documentType->name ?? '-' }}">
            </div>
        </div>
        <div class="mb-1 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Data de Expedição:</label>
            <div class="col-sm-10">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail"
                    value="{{ $employee->id_issue_date != null ? \Carbon\Carbon::parse($employee->id_issue_date)->isoFormat('DD/MM/Y') : '-' }}">
            </div>
        </div>
        <div class="mb-1 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Orgão Expedidor:</label>
            <div class="col-sm-10">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail"
                    value="{{ $employee->id_issue_agency ?? '-' }}">
            </div>
        </div>
        <div class="mb-1 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Estado Civil:</label>
            <div class="col-sm-10">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail"
                    value="{{ $employee->maritalStatus->name ?? '-' }}">
            </div>
        </div>
        <div class="mb-1 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Nome cônjuge:</label>
            <div class="col-sm-10">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail"
                    value="{{ $employee->spouse_name ?? '-' }}">
            </div>
        </div>
        <div class="mb-1 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Nome do pai:</label>
            <div class="col-sm-10">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail"
                    value="{{ $employee->father_name ?? '-' }}">
            </div>
        </div>
        <div class="mb-1 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Nome da mãe:</label>
            <div class="col-sm-10">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail"
                    value="{{ $employee->mother_name ?? '-' }}">
            </div>
        </div>
    </div>
</div>
&nbsp;
<div class="card">
    <div class="card-header" data-bs-toggle="collapse" href="#collapseContact" role="button" aria-expanded="true" aria-controls="collapseContact">> Endereço para Contato</div>
    <div class="collapse show card-body" id="collapseContact">
        <div class="mb-1 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Logradouro:</label>
            <div class="col-sm-10">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail"
                    value="{{ $employee->address_street ?? '-' }}">
            </div>
        </div>
        <div class="mb-1 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Complemento:</label>
            <div class="col-sm-10">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail"
                    value="{{ $employee->address_complement ?? '-' }}">
            </div>
        </div>
        <div class="mb-1 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Número:</label>
            <div class="col-sm-10">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail"
                    value="{{ $employee->address_number ?? '-' }}">
            </div>
        </div>
        <div class="mb-1 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Bairro:</label>
            <div class="col-sm-10">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail"
                    value="{{ $employee->address_district ?? '-' }}">
            </div>
        </div>
        <div class="mb-1 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">CEP:</label>
            <div class="col-sm-10">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail"
                    value="{{ $employee->address_postal_code ?? '-' }}">
            </div>
        </div>
        <div class="mb-1 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">UF:</label>
            <div class="col-sm-10">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail"
                    value="{{ $employee->addressState->uf ?? '-' }}">
            </div>
        </div>
        <div class="mb-1 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Cidade:</label>
            <div class="col-sm-10">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail"
                    value="{{ $employee->address_city ?? '-' }}">
            </div>
        </div>
        <div class="mb-1 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Código de Área:</label>
            <div class="col-sm-10">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail"
                    value="{{ $employee->area_code ?? '-' }}">
            </div>
        </div>
        <div class="mb-1 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Telefone:</label>
            <div class="col-sm-10">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail"
                    value="{{ $employee->phone ?? '-' }}">
            </div>
        </div>
        <div class="mb-1 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Celular:</label>
            <div class="col-sm-10">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail"
                    value="{{ $employee->mobile ?? '-' }}">
            </div>
        </div>
        <div class="mb-1 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Email:</label>
            <div class="col-sm-10">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail"
                    value="{{ $employee->email ?? '-' }}">
            </div>
        </div>
    </div>
</div>
@isset($employee->user)
    <div class="card">
        <div class="card-header" data-bs-toggle="collapse" href="#collapseSystem" role="button" aria-expanded="true" aria-controls="collapseSystem">Sistema</div>
        <div class="collapse show card-body" id="collapseSystem">
            <div class="mb-1 row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Usuário Vinculado:</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control-plaintext" id="staticEmail"
                        value="{{ $employee->user->id }}">
                </div>
            </div>
        </div>
    </div>
@endisset
<br />
<button type="button" onclick="history.back()" class="btn btn-secondary">Voltar</button>
<br /><br />
