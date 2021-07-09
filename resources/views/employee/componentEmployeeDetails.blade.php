@csrf

<h4>Dados Pessoais</h4><br />
Nome: {{ $employee->name  }}
<br /><br />
CPF: {{ $employee->cpf }}
<br /><br />
Profissão: {{ $employee->job }}
<br /><br />
Gênero: {{ $employee->gender->name }}
<br /><br />
Data de Nascimento: {{ \Carbon\Carbon::parse($employee->birthday)->isoFOrmat('DD/MM/Y') }}
<br /><br />
UF Nascimento: {{ $employee->birthState->name }}
<br /><br />
Cidade de Nascimento: {{ $employee->birth_city }}
<br /><br />
Número do Documento: {{ $employee->id_number }}
<br /><br />
Tipo de Documento: {{ $employee->idType->name }}
<br /><br />
Data de Expedição: {{ \Carbon\Carbon::parse($employee->id_issue_date)->isoFOrmat('DD/MM/Y') }}
<br /><br />
Orgão Expedidor: {{ $employee->id_issue_agency }}
<br /><br />
Estado Civil: {{ $employee->maritalStatus->name }}
<br /><br />
Nome cônjuge: {{ $employee->spouse_name }}
<br /><br />
Nome do pai: {{ $employee->father_name }}
<br /><br />
Nome da mãe: {{ $employee->mother_name }}
<br /><br />
<h4>Endereço para Contato</h4><br />
Logradouro: {{ $employee->address_street }}
<br /><br />
Complemento: {{ $employee->address_complement }}
<br /><br />
Número: {{ $employee->address_number }}
<br /><br />
Bairro: {{ $employee->address_district }}
<br /><br />
CEP: {{ $employee->address_postal_code }}
<br /><br />
UF: {{ $employee->addressState->uf }}
<br /><br />
Cidade: {{ $employee->address_city }}
<br /><br />
Código de Área: {{ $employee->area_code }}
<br /><br />
Telefone: {{ $employee->phone }}
<br /><br />
Celular: {{ $employee->mobile }}
<br /><br />
Email: {{ $employee->email }}
<br /><br />
Usuário Vinculado: {{ $employee->user->id }}
<br /><br />
<input type="button" value="Voltar" onclick="back();">
<br /><br />
