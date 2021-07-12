{{-- <style>
    h3 {
        background-color: rgb(180, 180, 180);
    }

    h4 {
        background-color: rgb(230, 230, 230);
    }

</style> --}}

<h3>== FORMULÁRIO UAB ==</h3><br />
<h4>> Dados Pessoais</h4><br />
Nome: {{ $approved->name }}
<br /><br />
CPF: {{ $approved->cpf }}
<br /><br />
Profissão: {{ $approved->job }}
<br /><br />
Gênero: {{ $approved->gender->name }}
<br /><br />
Data de Nascimento: {{ \Carbon\Carbon::parse($approved->birthday)->isoFormat('DD/MM/Y') }}
<br /><br />
UF Nascimento: {{ $approved->birthState->name }}
<br /><br />
Cidade de Nascimento: {{ $approved->birth_city }}
<br /><br />
Número do Documento: {{ $approved->id_number }}
<br /><br />
Tipo de Documento: {{ $approved->idType->name }}
<br /><br />
Data de Expedição: {{ \Carbon\Carbon::parse($approved->id_issue_date)->isoFormat('DD/MM/Y') }}
<br /><br />
Orgão Expedidor: {{ $approved->id_issue_agency }}
<br /><br />
Estado Civil: {{ $approved->maritalStatus->name }}
<br /><br />
Nome cônjuge: {{ $approved->spouse_name }}
<br /><br />
Nome do pai: {{ $approved->father_name }}
<br /><br />
Nome da mãe: {{ $approved->mother_name }}
<br /><br />
<h4>> Endereço para Contato</h4><br />
Logradouro: {{ $approved->address_street }}
<br /><br />
Complemento: {{ $approved->address_complement }}
<br /><br />
Número: {{ $approved->address_number }}
<br /><br />
Bairro: {{ $approved->address_district }}
<br /><br />
CEP: {{ $approved->address_postal_code }}
<br /><br />
UF: {{ $approved->addressState->uf }}
<br /><br />
Cidade: {{ $approved->address_city }}
<br /><br />
Código de Área: {{ $approved->area_code }}
<br /><br />
Telefone: {{ $approved->phone }}
<br /><br />
Celular: {{ $approved->mobile }}
<br /><br />
Email: {{ $approved->email }}
<br /><br />
<h3>== SISTEMA ==</h3><br />
@isset($approved->user)
    Usuário Vinculado: {{ $approved->user->id }}
@endisset
<br /><br />
<button type="button" onclick="history.back()">Voltar</button>
<br /><br />
