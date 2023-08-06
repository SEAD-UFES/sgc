<script>
    function validate(elementId) {
        var element = document.getElementById(elementId);
        element.value = element.value.replace(/[^0-9]+/, '');
    };

    function setCode() {
        let element = document.getElementById('inputMobile1');
        cellphone = element.value;
        cellphone = cellphone.replace(/\D/g,'');

        let mobileRegexp = /^([1-9]{1}[1-9]{1})?(9[6-9]\d{7})$/;
        let code = mobileRegexp.exec(cellphone);

        document.getElementById('inputArea1').value = code[1];
    }
</script>
@vite('resources/js/enable_inputmask.ts')
@vite('resources/js/enable_searchable_select.ts')

@csrf

<div class="row g-3 mb-3">
    <div class="col-12 col-sm-12 col-md-5 col-lg-5">
        <label for="inputName1" class="form-label">Nome*</label>
        <input name="name" type="text" id="inputName1" class="form-control" placeholder="Nome" value="{{ isset($applicant) ? $applicant->name : old('name') }}" maxlength="50" required />
        @error('name')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-12 col-sm-12 col-md-5 col-lg-5">
        <label for="inputEmail1" class="form-label">Email*</label>
        <input name="email" type="email" class="form-control" placeholder="Email" id="inputEmail1" value="{{ isset($applicant) ? $applicant->email : old('email') }}" required />
        @error('email')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row g-3 mb-3">
    <div class="col-5 col-sm-5 col-md-2 col-lg-2">
        <label for="inputLandline1" class="form-label">Fixo</label>
        <input name="landline" id="inputLandline1" type="tel" class="form-control" placeholder="Telefone Fixo" value="{{ isset($applicant) ? $applicant->landline : old('landline') }}" maxlength="14" data-inputmask="'mask': '(99) 9999-9999', 'removeMaskOnSubmit': true" />
        @error('landline')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-5 col-sm-5 col-md-2 col-lg-2">
        <label for="inputMobile1" class="form-label">Celular*</label>
        <input name="mobile" id="inputMobile1" type="tel" class="form-control" placeholder="Telefone Celular" value="{{ isset($applicant) ? $applicant->mobile : old('mobile') }}" maxlength="16" data-inputmask="'mask': '(99) 9 9999-9999', 'removeMaskOnSubmit': true" onfocusout="setCode()" />
        @error('mobile')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-2 col-sm-2 col-md-1 col-lg-1">
        <label for="inputArea1" class="form-label">DDD*</label>
        <input name="area_code" id="inputArea1" type="number" class="form-control" placeholder="DDD" value="{{ isset($applicant) ? $applicant->area_code : old('area_code') }}" maxlength="2" onkeyup="validate('inputArea1');" pattern="[0-9]{2}" />
        @error('area_code')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-5 col-sm-5 col-md-2 col-lg-2">
        <label for="inputHiringProcess1" class="form-label">Edital*</label>
        <input name="hiring_process" id="inputHiringProcess1" type="text" class="form-control" placeholder="NN/AAAA" value="{{ isset($applicant) ? $applicant->hiring_process : old('hiring_process') }}" maxlength="8" size="8" />
    </div>
    @error('hiring_process')
        <div class="text-danger">> {{ $message }}</div>
    @enderror
</div>
<div class="row g-3 mb-3">
    <div class="col-5 col-sm-5 col-md-4 col-lg-4">
        <label for="selectRoles1" class="form-label">Função*</label>
        <select name="role_id" id="selectRoles1" class="form-select select-dropdown">
            <option value="">Selecione a Função</option>
            @foreach ($roles as $role)
                <option value="{{ $role->id }}">
                    {{ $role->name }}
                </option>
            @endforeach
        </select>
        @error('role_id')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-5 col-sm-5 col-md-4 col-lg-4">
        <label for="selectCourses1" class="form-label">Curso</label>
        <select name="course_id" id="selectCourses1" class="form-select select-dropdown">
            <option value="">Não se aplica</option>
            @foreach ($courses as $course)
                <option value="{{ $course->id }}">
                    {{ $course->name }}
                </option>
            @endforeach
        </select>
        @error('course_id')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-5 col-sm-5 col-md-2 col-lg-2">
        <label for="selectPoles1" class="form-label">Polo</label>
        <select name="pole_id" id="selectPoles1" class="form-select select-dropdown">
            <option value="">Não se aplica</option>
            @foreach ($poles as $pole)
                <option value="{{ $pole->id }}">{{ $pole->name }}</option>
            @endforeach
        </select>
        @error('pole_id')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
</div>
