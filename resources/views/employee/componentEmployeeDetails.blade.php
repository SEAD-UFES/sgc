<h3>Formulário UAB</h3>
<div class="card">
    <div class="card-header" data-bs-toggle="collapse" href="#collapsePersonal" role="button" aria-expanded="true" aria-controls="collapsePersonal">> Dados Pessoais</div>
    <div class="collapse show card-body" id="collapsePersonal">
        <div class="mb-2 row">
            <div class="col-sm-4 col-lg-3">Nome:</div>
            <div class="col-sm-8 col-lg-9">{{ $employee->name ?? '-' }}</div>
        </div>
        <div class="mb-2 row">
            <div class="col-sm-4 col-lg-3">CPF:</div>
            <div class="col-sm-8 col-lg-9">{{ $employee->cpf ?? '-' }}
            </div>
        </div>
        <div class="mb-2 row">
            <div class="col-sm-4 col-lg-3">Profissão:</div>
            <div class="col-sm-8 col-lg-9">{{ $employee->job ?? '-' }}
            </div>
        </div>
        <div class="mb-2 row">
            <div class="col-sm-4 col-lg-3">Gênero:</div>
            <div class="col-sm-8 col-lg-9">{{ $employee->gender->name ?? '-' }}
            </div>
        </div>
        <div class="mb-2 row">
            <div class="col-sm-4 col-lg-3">Data de Nascimento:</div>
            <div class="col-sm-8 col-lg-9">{{ $employee->birthday != null ? \Carbon\Carbon::parse($employee->birthday)->isoFormat('DD/MM/Y') : '-' }}
            </div>
        </div>
        <div class="mb-2 row">
            <div class="col-sm-4 col-lg-3">UF Nascimento:</div>
            <div class="col-sm-8 col-lg-9">{{ $employee->birthState->uf ?? '-' }}
            </div>
        </div>
        <div class="mb-2 row">
            <div class="col-sm-4 col-lg-3">Cidade de Nascimento:</div>
            <div class="col-sm-8 col-lg-9">{{ $employee->birth_city ?? '-' }}
            </div>
        </div>
        <div class="mb-2 row">
            <div class="col-sm-4 col-lg-3">Tipo de Documento:</div>
            <div class="col-sm-8 col-lg-9">{{ $employee->documentType->name ?? '-' }}
            </div>
        </div>
        <div class="mb-2 row">
            <div class="col-sm-4 col-lg-3">Número do Documento:</div>
            <div class="col-sm-8 col-lg-9">{{ $employee->id_number ?? '-' }}
            </div>
        </div>
        <div class="mb-2 row">
            <div class="col-sm-4 col-lg-3">Data de Expedição:</div>
            <div class="col-sm-8 col-lg-9">{{ $employee->id_issue_date != null ? \Carbon\Carbon::parse($employee->id_issue_date)->isoFormat('DD/MM/Y') : '-' }}
            </div>
        </div>
        <div class="mb-2 row">
            <div class="col-sm-4 col-lg-3">Orgão Expedidor:</div>
            <div class="col-sm-8 col-lg-9">{{ $employee->id_issue_agency ?? '-' }}
            </div>
        </div>
        <div class="mb-2 row">
            <div class="col-sm-4 col-lg-3">Estado Civil:</div>
            <div class="col-sm-8 col-lg-9">{{ $employee->maritalStatus->name ?? '-' }}
            </div>
        </div>
        <div class="mb-2 row">
            <div class="col-sm-4 col-lg-3">Nome cônjuge:</div>
            <div class="col-sm-8 col-lg-9">{{ $employee->spouse_name ?? '-' }}
            </div>
        </div>
        <div class="mb-2 row">
            <div class="col-sm-4 col-lg-3">Nome do pai:</div>
            <div class="col-sm-8 col-lg-9">{{ $employee->father_name ?? '-' }}
            </div>
        </div>
        <div class="mb-2 row">
            <div class="col-sm-4 col-lg-3">Nome da mãe:</div>
            <div class="col-sm-8 col-lg-9">{{ $employee->mother_name ?? '-' }}
            </div>
        </div>
    </div>
</div>
&nbsp;
<div class="card">
    <div class="card-header" data-bs-toggle="collapse" href="#collapseContact" role="button" aria-expanded="true" aria-controls="collapseContact">> Endereço para Contato</div>
    <div class="collapse show card-body" id="collapseContact">
        <div class="mb-2 row">
            <div class="col-sm-4 col-lg-3">Logradouro:</div>
            <div class="col-sm-8 col-lg-9">{{ $employee->address_street ?? '-' }}
            </div>
        </div>
        <div class="mb-2 row">
            <div class="col-sm-4 col-lg-3">Complemento:</div>
            <div class="col-sm-8 col-lg-9">{{ $employee->address_complement ?? '-' }}
            </div>
        </div>
        <div class="mb-2 row">
            <div class="col-sm-4 col-lg-3">Número:</div>
            <div class="col-sm-8 col-lg-9">{{ $employee->address_number ?? '-' }}
            </div>
        </div>
        <div class="mb-2 row">
            <div class="col-sm-4 col-lg-3">Bairro:</div>
            <div class="col-sm-8 col-lg-9">{{ $employee->address_district ?? '-' }}
            </div>
        </div>
        <div class="mb-2 row">
            <div class="col-sm-4 col-lg-3">CEP:</div>
            <div class="col-sm-8 col-lg-9">{{ $employee->address_postal_code ?? '-' }}
            </div>
        </div>
        <div class="mb-2 row">
            <div class="col-sm-4 col-lg-3">UF:</div>
            <div class="col-sm-8 col-lg-9">{{ $employee->addressState->uf ?? '-' }}
            </div>
        </div>
        <div class="mb-2 row">
            <div class="col-sm-4 col-lg-3">Cidade:</div>
            <div class="col-sm-8 col-lg-9">{{ $employee->address_city ?? '-' }}
            </div>
        </div>
        <div class="mb-2 row">
            <div class="col-sm-4 col-lg-3">Código de Área:</div>
            <div class="col-sm-8 col-lg-9">{{ $employee->area_code ?? '-' }}
            </div>
        </div>
        <div class="mb-2 row">
            <div class="col-sm-4 col-lg-3">Telefone:</div>
            <div class="col-sm-8 col-lg-9"><a href='tel:{{ $employee->phone }}'>{{ $employee->phone ?? '-' }}</a>
            </div>
        </div>
        <div class="mb-2 row">
            <div class="col-sm-4 col-lg-3">Celular:</div>
            <div class="col-sm-8 col-lg-9"><a href='tel:{{ $employee->mobile }}'>{{ $employee->mobile ?? '-' }}</a>
            </div>
        </div>
        <div class="mb-2 row">
            <div class="col-sm-4 col-lg-3">Email:</div>
            <div class="col-sm-8 col-lg-9"><a href='mailto:{{ $employee->email }}'>{{ $employee->email ?? '-' }}</a>
            </div>
        </div>
    </div>
</div>
@isset($employee->user)
    <div class="card">
        <div class="card-header" data-bs-toggle="collapse" href="#collapseSystem" role="button" aria-expanded="true" aria-controls="collapseSystem">Sistema</div>
        <div class="collapse show card-body" id="collapseSystem">
            <div class="mb-2 row">
                <div class="col-sm-4 col-lg-3">Usuário Vinculado:</div>
                <div class="col-sm-8 col-lg-9">{{ $employee->user->id }}
                </div>
            </div>
        </div>
    </div>
@endisset
<br />
<button type="button" onclick="history.back()" class="btn btn-secondary">Voltar</button>
<br /><br />
