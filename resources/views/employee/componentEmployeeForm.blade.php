<script>
    function validate(elementId) {
        var element = document.getElementById(elementId);
        element.value = element.value.replace(/[^a-zA-Z0-9]+/, '');
    };
        
    $(document).ready(function(){
        Inputmask().mask(document.querySelectorAll("input"));
    });
</script>

@csrf

<div class="row g-3 mb-3">
    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
        <label for="inputName1" class="form-label">Nome*</label>
        <input name="name" id="inputName1" type="text" class="form-control" placeholder="Nome"
            value="{{ isset($employee) ? $employee->name : old('name') }}" maxlength="50" required />
        @error('name')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-4 col-sm-3 col-md-3 col-lg-2">
        <label for="inputCpf1" class="form-label">CPF*</label>
        <input name="cpf" id="inputCpf1" type="text" class="form-control" placeholder="CPF"
            value="{{ (isset($employee) and !$fromApproved) ? $employee->cpf : old('cpf') }}" maxlength="14" onkeyup="validate('inputCpf1')"
            data-inputmask="'mask': '999.999.999-99', 'removeMaskOnSubmit': true"
            required />
        @error('cpf')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-8 col-sm-9 col-md-3 col-lg-4">
        <label for="inputJob1" class="form-label">Profissão</label>
        <input name="job" id="inputJob1" type="text" class="form-control" placeholder="Profissão"
            value="{{ (isset($employee) and !$fromApproved) ? $employee->job : old('job') }}" maxlength="50" />
        @error('job')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
        <label for="selectGender1" class="form-label">Gênero</label>
        <select name="gender" id="selectGender1" class="form-select">
            <option value="">Gênero</option>
            @foreach ($genders as $gender)
                <option value="{{ $gender }}"
                    {{ (isset($employee) and !$fromApproved) ? ($employee->gender?->value == $gender ? 'selected' : '') : (old('gender') == $gender ? 'selected' : '') }}>
                    {{ $gender }}</option>
            @endforeach
        </select>
        @error('gender')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-6 col-sm-4 col-md-3 col-lg-3">
        <label for="inputBirthday1" class="form-label">Dt de Nascimento</label>
        <input name="birthday" id="inputBirthday1" type="date"
            value="{{ (isset($employee) and !$fromApproved) ? $employee->birthday : old('birthday') }}" class="form-control" />
        @error('birthday')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-4 col-sm-4 col-md-3 col-lg-2">
        <label for="selectBirthState1" class="form-label">UF de Nascimento</label>
        <select name="birth_state_id" id="selectBirthState1" class="form-select">
            <option value="">UF</option>
            @foreach ($birthStates as $birthState)
                <option value="{{ $birthState->id }}"
                    {{ (isset($employee) and !$fromApproved) ? ($employee->birth_state_id == $birthState->id ? 'selected' : '') : (old('birth_state_id') == $birthState->id ? 'selected' : '') }}>
                    {{ $birthState->uf }}
                </option>
            @endforeach
        </select>
        @error('birth_state_id')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-8 col-sm-8 col-md-3 col-lg-5">
        <label for="inputBirthCity1" class="form-label">Cidade de Nascimento</label>
        <input name="birth_city" type="text" id="inputBirthCity1" class="form-control" placeholder="Cidade"
            value="{{ (isset($employee) and !$fromApproved) ? $employee->birth_city : old('birth_city') }}" maxlength="50" />
        @error('birth_city')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-7 col-sm-8 col-md-6 col-lg-4">
        <label for="selectIdType1" class="form-label">Tipo de Documento</label>
        <select name="document_type_id" id="selectIdType1" class="form-select">
            <option value="">Selecione o tipo</option>
            @foreach ($documentTypes as $documentType)
                <option value="{{ $documentType->id }}"
                    {{ (isset($employee) and !$fromApproved) ? ($employee->document_type_id == $documentType->id ? 'selected' : '') : (old('document_type_id') == $documentType->id ? 'selected' : '') }}>
                    {{ $documentType->name }}</option>
            @endforeach
        </select>
        @error('document_type_id')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-5 col-sm-4 col-md-3 col-lg-3">
        <label for="inputId1" class="form-label">Núm. do Documento</label>
        <input name="id_number" id="inputId1" type="text" class="form-control" placeholder="Número"
            value="{{ (isset($employee) and !$fromApproved) ? $employee->id_number : old('id_number') }}" maxlength="15" />
        @error('id_number')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-5 col-sm-4 col-md-3 col-lg-3">
        <label for="inputIssueDate1" class="form-label">Data de Expedição</label>
        <input name="id_issue_date" id="inputIssueDate1" type="date" class="form-control"
            value="{{ (isset($employee) and !$fromApproved) ? $employee->id_issue_date : old('id_issue_date') }}" />
        @error('id_issue_date')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-7 col-sm-8 col-md-3 col-lg-2">
        <label for="inpuIssueAgency1" class="form-label">Orgão Expedidor</label>
        <input name="id_issue_agency" id="inpuIssueAgency1" type="text" class="form-control" placeholder="Orgão"
            value="{{ (isset($employee) and !$fromApproved) ? $employee->id_issue_agency : old('id_issue_agency') }}" maxlength="10" />
        @error('id_issue_agency')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row g-3 mb-3">
    <div class="col-4 col-sm-4 col-md-3 col-lg-2">
        <label for="selectMarital1" class="form-label">Estado Civil</label>
        <select name="marital_status" id="selectMarital1" class="form-select">
            <option value="">Estado Civil</option>
            @foreach ($maritalStatuses as $maritalStatus)
                <option value="{{ $maritalStatus }}"
                    {{ (isset($employee) and !$fromApproved) ? ($employee->marital_status?->value == $maritalStatus ? 'selected' : '') : (old('marital_status') == $maritalStatus ? 'selected' : '') }}>
                    {{ $maritalStatus }}
                </option>
            @endforeach
        </select>
        @error('marital_status')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-8 col-sm-8 col-md-9 col-lg-10">
        <label for="inputSpouse1" class="form-label">Nome cônjuge</label>
        <input name="spouse_name" id="inputSpouse1" type="text" class="form-control" placeholder="Nome"
            value="{{ (isset($employee) and !$fromApproved) ? $employee->spouse_name : old('spouse_name') }}" maxlength="50" />
        @error('spouse_name')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-6 col-sm-6 col-md-6 col-lg-6">
        <label for="inputFather1" class="form-label">Nome do pai</label>
        <input name="father_name" id="inputFather1" type="text" class="form-control" placeholder="Nome"
            value="{{ (isset($employee) and !$fromApproved) ? $employee->father_name : old('father_name') }}" maxlength="50" />
        @error('father_name')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-6 col-sm-6 col-md-6 col-lg-6">
        <label for="inputMother1" class="form-label">Nome da mãe</label>
        <input name="mother_name" id="inputMother1" type="text" class="form-control" placeholder="Nome"
            value="{{ (isset($employee) and !$fromApproved) ? $employee->mother_name : old('mother_name') }}" maxlength="50" />
        @error('mother_name')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row g-3 mb-3">
    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
        <label for="inputStreet1" class="form-label">Logradouro</label>
        <input name="address_street" id="inputStreet1" type="text" class="form-control" placeholder="Logradouro"
            value="{{ (isset($employee) and !$fromApproved) ? $employee->address_street : old('address_street') }}" maxlength="50" />
        @error('address_street')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
        <label for="inputComplement1" class="form-label">Complemento</label>
        <input name="address_complement" id="inputComplement1" type="text" class="form-control"
            placeholder="Complemento"
            value="{{ (isset($employee) and !$fromApproved) ? $employee->address_complement : old('address_complement') }}"
            maxlength="50" />
        @error('address_complement')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-3 col-sm-3 col-md-2 col-lg-1">
        <label for="inputNumber1" class="form-label">Número</label>
        <input name="address_number" id="inputNumber1" type="text" class="form-control" placeholder="Número"
            value="{{ (isset($employee) and !$fromApproved) ? $employee->address_number : old('address_number') }}" maxlength="5" />
        @error('address_number')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-6 col-sm-6 col-md-3 col-lg-4">
        <label for="inputDistrict1" class="form-label">Bairro</label>
        <input name="address_district" id="inputDistrict1" type="text" class="form-control" placeholder="Bairro"
            value="{{ (isset($employee) and !$fromApproved) ? $employee->address_district : old('address_district') }}" maxlength="50" />
        @error('address_district')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-3 col-sm-3 col-md-2 col-lg-2">
        <label for="inputPostal1" class="form-label">CEP</label>
        <input name="address_postal_code" id="inputPostal1" type="text" class="form-control"
            placeholder="CEP"
            value="{{ (isset($employee) and !$fromApproved) ? $employee->address_postal_code : old('address_postal_code') }}" maxlength="9"
            data-inputmask="'mask': '99999-999', 'removeMaskOnSubmit': true"
            onkeyup="validate('inputPostal1');" />
        @error('address_postal_code')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-3 col-sm-2 col-md-2 col-lg-2">
        <label for="selectAddressState1" class="form-label">UF</label>
        <select name="address_state_id" id="selectAddressState1" class="form-select">
            <option value="">UF</option>
            @foreach ($addressStates as $addressState)
                <option value="{{ $addressState->id }}"
                    {{ (isset($employee) and !$fromApproved) ? ($employee->address_state_id == $addressState->id ? 'selected' : '') : (old('address_state_id') == $addressState->id ? 'selected' : '') }}>
                    {{ $addressState->uf }}
                </option>
            @endforeach
        </select>
        @error('address_state_id')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-9 col-sm-10 col-md-3 col-lg-3">
        <label for="inputAddressCity1" class="form-label">Cidade</label>
        <input name="address_city" id="inputAddressCity1" type="text" class="form-control" placeholder="Cidade"
            value="{{ (isset($employee) and !$fromApproved) ? $employee->address_city : old('address_city') }}" maxlength="50" />
        @error('address_city')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row g-3 mb-3">
    <div class="col-2 col-sm-2 col-md-1 col-lg-1">
        <label for="inputArea1" class="form-label">DDD</label>
        <input name="area_code" id="inputArea1" type="text" class="form-control" placeholder="DDD"
            value="{{ isset($employee) ? $employee->area_code : old('area_code') }}" maxlength="2"
            onkeyup="validate('inputArea1');" pattern="[0-9]{2}" />
        @error('area_code')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-5 col-sm-5 col-md-2 col-lg-2">
        <label for="inputPhone1" class="form-label">Telefone</label>
        <input name="phone" id="inputPhone1" type="tel" class="form-control" placeholder="Telefone"
            value="{{ isset($employee) ? $employee->phone : old('phone') }}" maxlength="14"
            data-inputmask="'mask': '(99) 9999-9999', 'removeMaskOnSubmit': true" />
        @error('phone')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-5 col-sm-5 col-md-2 col-lg-2">
        <label for="inputMobile1" class="form-label">Celular</label>
        <input name="mobile" id="inputMobile1" type="tel" class="form-control" placeholder="Celular"
            value="{{ isset($employee) ? $employee->mobile : old('mobile') }}" maxlength="16"
            data-inputmask="'mask': '(99) 9 9999-9999', 'removeMaskOnSubmit': true" />
        @error('mobile')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-12 col-sm-12 col-md-7 col-lg-7">
        <label for="inputEmail1" class="form-label">Email*</label>
        <input name="email" id="inputEmail1" type="email" class="form-control" placeholder="Email"
            value="{{ isset($employee) ? $employee->email : old('email') }}" required />
        @error('email')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row g-3 mb-3">
    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
        <label for="inputBank1" class="form-label">Banco</label>
        <input name="bank_name" id="inputBank1" type="text" class="form-control" placeholder="Banco"
            value="{{ isset($employee) && isset($employee->bankAccount) ? $employee->bankAccount->bank_name : old('bank_name') }}" maxlength="100" />
        @error('bank_name')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-6 col-sm-6 col-md-3 col-lg-3">
        <label for="inputAgency1" class="form-label">Agência</label>
        <input name="agency_number" id="inputAgency1" type="text" class="form-control" placeholder="Agência"
            value="{{ isset($employee) && isset($employee->bankAccount) ? $employee->bankAccount->agency_number : old('agency_number') }}" maxlength="100" />
        @error('agency_number')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-6 col-sm-6 col-md-3 col-lg-3">
        <label for="inputAccount1" class="form-label">Conta Corrente</label>
        <input name="account_number" id="inputAccount1" type="text" class="form-control" placeholder="Conta Corrente"
            value="{{ isset($employee) && isset($employee->bankAccount) ? $employee->bankAccount->account_number : old('account_number') }}" maxlength="100" />
        @error('account_number')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
</div>
