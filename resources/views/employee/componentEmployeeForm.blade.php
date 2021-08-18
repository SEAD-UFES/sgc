<script>
    function validate(elementId) {
        var element = document.getElementById(elementId);
        element.value = element.value.replace(/[^a-zA-Z0-9]+/, '');
    };
</script>

@csrf

<div class="row g-3 mb-3">
    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
        <label for="inputName1" class="form-label">Nome*</label>
        <input name="name" type="text" id="inputName1" class="form-control" placeholder="Nome"
            value="{{ $employee->name ?? old('name') }}" maxlength="50" required />
        @error('name')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-4 col-sm-3 col-md-3 col-lg-2">
        <label for="inputCpf1" class="form-label">CPF*</label>
        <input name="cpf" id="cpf" type="text" id="inputCpf1" class="form-control" placeholder="CPF"
            value="{{ $employee->cpf ?? old('cpf') }}" maxlength="11" onkeyup="validate('cpf')" required />
        @error('cpf')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-8 col-sm-9 col-md-3 col-lg-4">
        <label for="inputJob1" class="form-label">Profissão</label>
        <input name="job" type="text" id="inputJob1" class="form-control" placeholder="Profissão"
            value="{{ $employee->job ?? old('job') }}" maxlength="50" />
        @error('job')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
        <label for="selectGender1" class="form-label">Gênero</label>
        <select name="genders" id="selectGender1" class="form-select">
            <option value="">Gênero</option>
            @foreach ($genders as $gender)
                <option value="{{ $gender->id }}" {{ $employee->gender_id == $gender->id ? 'selected' : '' }}>
                    {{ $gender->name }}</option>
            @endforeach
        </select>
        @error('gender')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-6 col-sm-4 col-md-3 col-lg-3">
        <label for="inputBirthday1" class="form-label">Dt de Nascimento</label>
        <input name="birthday" type="date" id="inputBirthday1" value="{{ $employee->birthday ?? old('birthday') }}"
            class="form-control" />
        @error('birthday')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-4 col-sm-4 col-md-3 col-lg-2">
        <label for="selectBirthState1" class="form-label">UF de Nascimento</label>
        <select name="birthStates" id="selectBirthState1" class="form-select">
            <option value="">UF</option>
            @foreach ($birthStates as $birthState)
                <option value="{{ $birthState->id }}"
                    {{ $employee->birth_state_id == $birthState->id ? 'selected' : '' }}>{{ $birthState->uf }}
                </option>
            @endforeach
        </select>
        @error('birthStates')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-8 col-sm-8 col-md-3 col-lg-5">
        <label for="inputBirthCity1" class="form-label">Cidade de Nascimento</label>
        <input name="birthCity" type="text" id="inputBirthCity1" class="form-control" placeholder="Cidade"
            value="{{ $employee->birth_city ?? old('birthCity') }}" maxlength="50" />
        @error('birthCity')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-7 col-sm-8 col-md-6 col-lg-4">
        <label for="selectIdType1" class="form-label">Tipo de Documento</label>
        <select name="documentTypes" id="selectIdType1" class="form-select">
            <option value="">Selecione o tipo</option>
            @foreach ($documentTypes as $documentType)
                <option value="{{ $documentType->id }}"
                    {{ $employee->document_type_id == $documentType->id ? 'selected' : '' }}>
                    {{ $documentType->name }}</option>
            @endforeach
        </select>
        @error('documentType')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-5 col-sm-4 col-md-3 col-lg-3">
        <label for="inputId1" class="form-label">Núm. do Documento</label>
        <input name="idNumber" type="text" id="inputId1" class="form-control" placeholder="Número"
            value="{{ $employee->id_number ?? old('idNumber') }}" maxlength="15" />
        @error('idNumber')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-5 col-sm-4 col-md-3 col-lg-3">
        <label for="inputIssueDate1" class="form-label">Data de Expedição</label>
        <input name="idIssueDate" type="date" id="inputIssueDate1" class="form-control"
            value="{{ $employee->id_issue_date ?? old('idIssueDate') }}" />
        @error('idIssueDate')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-7 col-sm-8 col-md-3 col-lg-2">
        <label for="inpuIssueAgency1" class="form-label">Orgão Expedidor</label>
        <input name="idIssueAgency" type="text" id="inpuIssueAgency1" class="form-control" placeholder="Orgão"
            value="{{ $employee->id_issue_agency ?? old('idIssueAgency') }}" maxlength="10" />
        @error('idIssueAgency')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row g-3 mb-3">
    <div class="col-4 col-sm-4 col-md-3 col-lg-2">
        <label for="selectMarital1" class="form-label">Estado Civil</label>
        <select name="maritalStatuses" id="selectMarital1" class="form-select">
            <option value="">Estado Civil</option>
            @foreach ($maritalStatuses as $maritalStatus)
                <option value="{{ $maritalStatus->id }}"
                    {{ $employee->marital_status_id == $maritalStatus->id ? 'selected' : '' }}>
                    {{ $maritalStatus->name }}
                </option>
            @endforeach
        </select>
        @error('maritalStatuses')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-8 col-sm-8 col-md-9 col-lg-10">
        <label for="inputSpouse1" class="form-label">Nome cônjuge</label>
        <input name="spouseName" type="text" id="inputSpouse1" class="form-control" placeholder="Nome"
            value="{{ $employee->spouse_name ?? old('spouseName') }}" maxlength="50" />
        @error('spouseName')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-6 col-sm-6 col-md-6 col-lg-6">
        <label for="inputFather1" class="form-label">Nome do pai</label>
        <input name="fatherName" type="text" id="inputFather1" class="form-control" placeholder="Nome"
            value="{{ $employee->father_name ?? old('fatherName') }}" maxlength="50" />
        @error('fatherName')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-6 col-sm-6 col-md-6 col-lg-6">
        <label for="inputMother1" class="form-label">Nome da mãe</label>
        <input name="motherName" type="text" id="inputMother1" class="form-control" placeholder="Nome"
            value="{{ $employee->mother_name ?? old('motherName') }}" maxlength="50" />
        @error('motherName')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row g-3 mb-3">
    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
        <label for="inputStreet1" class="form-label">Logradouro</label>
        <input name="addressStreet" type="text" id="inputStreet1" class="form-control" placeholder="Logradouro"
            value="{{ $employee->address_street ?? old('addressStreet') }}" maxlength="50" />
        @error('addressStreet')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
        <label for="inputComplement1" class="form-label">Complemento</label>
        <input name="addressComplement" type="text" id="inputComplement1" class="form-control" placeholder="Complemento"
            value="{{ $employee->address_complement ?? old('addressComplement') }}" maxlength="50" />
        @error('addressComplement')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-3 col-sm-3 col-md-2 col-lg-1">
        <label for="inputNumber1" class="form-label">Número</label>
        <input name="addressNumber" type="text" id="inputNumber1" class="form-control" placeholder="Número"
            value="{{ $employee->address_number ?? old('addressNumber') }}" maxlength="5" />
        @error('addressNumber')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-6 col-sm-6 col-md-3 col-lg-4">
        <label for="inputDistrict1" class="form-label">Bairro</label>
        <input name="addressDistrict" type="text" id="inputDistrict1" class="form-control" placeholder="Bairro"
            value="{{ $employee->address_district ?? old('addressDistrict') }}" maxlength="50" />
        @error('addressDistrict')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-3 col-sm-3 col-md-2 col-lg-2">
        <label for="addressPostalCode" class="form-label">CEP</label>
        <input name="addressPostalCode" id="addressPostalCode" type="text" id="inputPostal1" class="form-control"
            placeholder="CEP" value="{{ $employee->address_postal_code ?? old('addressPostalCode') }}" maxlength="8"
            onkeyup="validate('addressPostalCode');" />
        @error('addressPostalCode')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-3 col-sm-2 col-md-2 col-lg-2">
        <label for="selectAddressState1" class="form-label">UF</label>
        <select name="addressStates" id="selectAddressState1" class="form-select">
            <option value="">UF</option>
            @foreach ($addressStates as $addressState)
                <option value="{{ $addressState->id }}"
                    {{ $employee->address_state_id == $addressState->id ? 'selected' : '' }}>
                    {{ $addressState->uf }}
                </option>
            @endforeach
        </select>
        @error('addressStates')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-9 col-sm-10 col-md-3 col-lg-3">
        <label for="inputAddressCity1" class="form-label">Cidade</label>
        <input name="addressCity" type="text" id="inputAddressCity1" class="form-control" placeholder="Cidade"
            value="{{ $employee->address_city ?? old('addressCity') }}" maxlength="50" />
        @error('addressCity')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row g-3 mb-3">
    <div class="col-2 col-sm-2 col-md-1 col-lg-1">
        <label for="inputArea1" class="form-label">DDD</label>
        <input name="areaCode" id="areaCode" type="text" id="inputArea1" class="form-control" placeholder="DDD"
            value="{{ $employee->area_code ?? old('areaCode') }}" maxlength="3" onkeyup="validate('areaCode');" pattern="[0-9]{2}" />
        @error('areaCode')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-5 col-sm-5 col-md-2 col-lg-2">
        <label for="inputPhone1" class="form-label">Telefone</label>
        <input name="phone" id="phone" type="tel" id="inputPhone1" class="form-control" placeholder="Telefone"
            value="{{ $employee->phone ?? old('phone') }}" maxlength="10" onkeyup="validate('phone');" pattern="[0-9]{8}" />
        @error('phone')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-5 col-sm-5 col-md-2 col-lg-2">
        <label for="inputMobile1" class="form-label">Celular</label>
        <input name="mobile" id="mobile" type="tel" id="inputMobile1" class="form-control" placeholder="Celular"
            value="{{ $employee->mobile ?? old('mobile') }}" maxlength="10" onkeyup="validate('mobile');" pattern="[0-9]{9}" /><span class="validity"></span>
        @error('mobile')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-12 col-sm-12 col-md-7 col-lg-7">
        <label for="inputEmail1" class="form-label">Email*</label>
        <input name="email" type="email" class="form-control" placeholder="Email" id="inputEmail1"
            value="{{ $employee->email ?? old('email') }}" required />
        @error('email')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
</div>
