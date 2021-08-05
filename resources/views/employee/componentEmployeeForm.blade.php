<script>
    function validate(elementId) {
        var element = document.getElementById(elementId);
        element.value = element.value.replace(/[^a-zA-Z0-9]+/, '');
    };
</script>


@csrf

Nome*: <input name="name" type="text" placeholder="Nome" value="{{ $employee->name ?? old('name') }}"
    maxlength="50" />
@error('name')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
CPF*: <input name="cpf" id="cpf" type="text" placeholder="CPF" value="{{ $employee->cpf ?? old('cpf') }}"
    maxlength="11" onkeyup="validate('cpf')" />
@error('cpf')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
Profissão: <input name="job" type="text" placeholder="Profissão" value="{{ $employee->job ?? old('job') }}"
    maxlength="50" />
@error('job')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
Gênero: <select name="genders">
    <option value="">Selecione o gênero</option>
    @foreach ($genders as $gender)
        <option value="{{ $gender->id }}" {{ $employee->gender_id == $gender->id ? 'selected' : '' }}>
            {{ $gender->name }}</option>
    @endforeach
</select>
@error('gender')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
Data de Nascimento: <input name="birthday" type="date" value="{{ $employee->birthday ?? old('birthday') }}" />
@error('birthday')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
UF Nascimento: <select name="birthStates">
    <option value="">Selecione a UF</option>
    @foreach ($birthStates as $birthState)
        <option value="{{ $birthState->id }}"
            {{ $employee->birth_state_id == $birthState->id ? 'selected' : '' }}>{{ $birthState->uf }}</option>
    @endforeach
</select>
@error('birthStates')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
Cidade de Nascimento: <input name="birthCity" type="text" placeholder="Cidade"
    value="{{ $employee->birth_city ?? old('birthCity') }}" maxlength="50" />
@error('birthCity')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
Número do Documento: <input name="idNumber" type="text" placeholder="Número"
    value="{{ $employee->id_number ?? old('idNumber') }}" maxlength="15" />
@error('idNumber')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
Tipo de Documento: <select name="documentTypes">
    <option value="">Selecione o tipo</option>
    @foreach ($documentTypes as $documentType)
        <option value="{{ $documentType->id }}"
            {{ $employee->document_type_id == $documentType->id ? 'selected' : '' }}>
            {{ $documentType->name }}</option>
    @endforeach
</select>
@error('documentType')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
Data de Expedição: <input name="idIssueDate" type="date"
    value="{{ $employee->id_issue_date ?? old('idIssueDate') }}" />
@error('idIssueDate')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
Orgão Expedidor: <input name="idIssueAgency" type="text" placeholder="Orgão"
    value="{{ $employee->id_issue_agency ?? old('idIssueAgency') }}" maxlength="10" />
@error('idIssueAgency')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
Estado Civil: <select name="maritalStatuses">
    <option value="">Selecione o Estado Civil</option>
    @foreach ($maritalStatuses as $maritalStatus)
        <option value="{{ $maritalStatus->id }}"
            {{ $employee->marital_status_id == $maritalStatus->id ? 'selected' : '' }}>{{ $maritalStatus->name }}
        </option>
    @endforeach
</select>
@error('maritalStatuses')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
Nome cônjuge: <input name="spouseName" type="text" placeholder="Nome"
    value="{{ $employee->spouse_name ?? old('spouseName') }}" maxlength="50" />
@error('spouseName')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
Nome do pai: <input name="fatherName" type="text" placeholder="Nome"
    value="{{ $employee->father_name ?? old('fatherName') }}" maxlength="50" />
@error('fatherName')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
Nome da mãe: <input name="motherName" type="text" placeholder="Nome"
    value="{{ $employee->mother_name ?? old('motherName') }}" maxlength="50" />
@error('motherName')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
Logradouro: <input name="addressStreet" type="text" placeholder="Logradouro"
    value="{{ $employee->address_street ?? old('addressStreet') }}" maxlength="50" />
@error('addressStreet')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
Complemento: <input name="addressComplement" type="text" placeholder="Complemento"
    value="{{ $employee->address_complement ?? old('addressComplement') }}" maxlength="50" />
@error('addressComplement')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
Número: <input name="addressNumber" type="text" placeholder="Número"
    value="{{ $employee->address_number ?? old('addressNumber') }}" maxlength="5" />
@error('addressNumber')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
Bairro: <input name="addressDistrict" type="text" placeholder="Bairro"
    value="{{ $employee->address_district ?? old('addressDistrict') }}" maxlength="50" />
@error('addressDistrict')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
CEP: <input name="addressPostalCode" id="addressPostalCode" type="text" placeholder="CEP"
    value="{{ $employee->address_postal_code ?? old('addressPostalCode') }}" maxlength="8"
    onkeyup="validate('addressPostalCode');" />
@error('addressPostalCode')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
UF: <select name="addressStates">
    <option value="">Selecione a UF</option>
    @foreach ($addressStates as $addressState)
        <option value="{{ $addressState->id }}"
            {{ $employee->address_state_id == $addressState->id ? 'selected' : '' }}>{{ $addressState->uf }}
        </option>
    @endforeach
</select>
@error('addressStates')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
Cidade: <input name="addressCity" type="text" placeholder="Cidade"
    value="{{ $employee->address_city ?? old('addressCity') }}" maxlength="50" />
@error('addressCity')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
Código de Área: <input name="areaCode" id="areaCode" type="text" placeholder="Código"
    value="{{ $employee->area_code ?? old('areaCode') }}" maxlength="3" onkeyup="validate('areaCode');" />
@error('areaCode')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
Telefone: <input name="phone" id="phone" type="text" placeholder="Telefone"
    value="{{ $employee->phone ?? old('phone') }}" maxlength="10" onkeyup="validate('phone');" />
@error('phone')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
Celular: <input name="mobile" id="mobile" type="text" placeholder="Celular"
    value="{{ $employee->mobile ?? old('mobile') }}" maxlength="10" onkeyup="validate('mobile');" />
@error('mobile')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
Email*: <input name="email" type="email" placeholder="Email" value="{{ $employee->email ?? old('email') }}" />
@error('email')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
