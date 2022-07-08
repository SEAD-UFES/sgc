<script>
    function validate(elementId) {
        var element = document.getElementById(elementId);
        element.value = element.value.replace(/[^0-9]+/, '');
    };

    $(document).ready(function() {
        Inputmask().mask(document.querySelectorAll("input"));
    });
</script>

@csrf

<div class="row g-3 mb-3">
    <div class="col-12 col-sm-12 col-md-5 col-lg-5">
        <label for="inputName1" class="form-label">Nome*</label>
        <input name="name" type="text" id="inputName1" class="form-control" placeholder="Nome" value="{{ isset($approved) ? $approved->name : old('name') }}" maxlength="50" required />
        @error('name')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-12 col-sm-12 col-md-5 col-lg-5">
        <label for="inputEmail1" class="form-label">Email*</label>
        <input name="email" type="email" class="form-control" placeholder="Email" id="inputEmail1" value="{{ isset($approved) ? $approved->email : old('email') }}" required />
        @error('email')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row g-3 mb-3">
    <div class="col-2 col-sm-2 col-md-1 col-lg-1">
        <label for="inputArea1" class="form-label">DDD</label>
        <input name="area_code" id="inputArea1" type="text" class="form-control" placeholder="DDD" value="{{ isset($approved) ? $approved->area_code : old('area_code') }}" maxlength="2" onkeyup="validate('inputArea1');" pattern="[0-9]{2}" />
        @error('area_code')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-5 col-sm-5 col-md-2 col-lg-2">
        <label for="inputPhone1" class="form-label">Telefone</label>
        <input name="phone" id="inputPhone1" type="tel" class="form-control" placeholder="Telefone" value="{{ isset($approved) ? $approved->phone : old('phone') }}" maxlength="14" data-inputmask="'mask': '(99) 9999-9999', 'removeMaskOnSubmit': true" />
        @error('phone')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-5 col-sm-5 col-md-2 col-lg-2">
        <label for="inputMobile1" class="form-label">Celular*</label>
        <input name="mobile" id="inputMobile1" type="tel" class="form-control" placeholder="Celular" value="{{ isset($approved) ? $approved->mobile : old('mobile') }}" maxlength="16" data-inputmask="'mask': '(99) 9 9999-9999', 'removeMaskOnSubmit': true" />
        @error('mobile')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-5 col-sm-5 col-md-2 col-lg-2">
        <label for="inputAnnouncement1" class="form-label">Edital*</label>
        <input name="announcement" id="inputAnnouncement1" type="text" class="form-control" value="{{ isset($approved) ? $approved->announcement : old('announcement') }}" maxlength="8" size="8" />
    </div>
    @error('announcement')
        <div class="text-danger">> {{ $message }}</div>
    @enderror
</div>
<div class="row g-3 mb-3">
    <div class="col-5 col-sm-5 col-md-4 col-lg-4">
        <label for="selectRoles1" class="form-label">Função*</label>
        <select name="role_id" id="selectRoles1" class="form-select" data-live-search="true">
            <option value="">Selecione a Função</option>
            @foreach ($roles as $role)
                <option value="{{ $role->id }}" {{ isset($approved) ? ($approved->role_id == $role->id ? 'selected' : '') : ($role->id == 1 ? 'selected' : '') }}>{{ $role->name }}</option>
            @endforeach
        </select>
        @error('role_id')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-5 col-sm-5 col-md-4 col-lg-4">
        <label for="selectCourses1" class="form-label">Curso*</label>
        <select name="course_id" id="selectCourses1" class="form-select" data-live-search="true">
            <option value="">Selecione o curso</option>
            @foreach ($courses as $course)
                <option value="{{ $course->id }}" {{ isset($approved) ? ($approved->course_id == $course->id ? 'selected' : '') : ($course->id == 1 ? 'selected' : '') }}>{{ $course->name }}</option>
            @endforeach
        </select>
        @error('course_id')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-5 col-sm-5 col-md-2 col-lg-2">
        <label for="selectPoles1" class="form-label">Polo*</label>
        <select name="pole_id" id="selectPoles1" class="form-select" data-live-search="true">
            <option value="">Selecione o polo</option>
            @foreach ($poles as $pole)
                <option value="{{ $pole->id }}" {{ isset($approved) ? ($approved->pole_id == $pole->id ? 'selected' : '') : ($pole->id == 1 ? 'selected' : '') }}>{{ $pole->name }}</option>
            @endforeach
        </select>
        @error('pole_id')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
</div>