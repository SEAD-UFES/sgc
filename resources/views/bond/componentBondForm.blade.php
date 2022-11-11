<script>
    $(document).ready(function(){
        Inputmask().mask(document.querySelectorAll("input"));
    });
</script>

@csrf
<div class="row g-3 mb-3">
    <div class="col-12 col-lg-6">
        <label for="selectEmployee1" class="form-label">Colaborador*</label>
        <select name="employee_id" id="selectEmployee1" class="form-select searchable-select">
            <option value="">Selecione o colaborador</option>
            @foreach ($employees as $employee)
                <option value="{{ $employee->id }}"
                    {{ isset($bond) ? ($bond->employee_id == $employee->id ? 'selected' : '') : (old('employee_id') == $employee->id ? 'selected' : '') }}>
                    {{ $employee->name . ' - ' . $employee->cpf }}</option>
            @endforeach
        </select>
        @error('employee_id')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-12 col-lg-6">
        <label for="selectRole1" class="form-label">Função*</label>
        <select name="role_id" id="selectRole1" class="form-select">
            <option value="">Selecione a Função</option>
            @foreach ($roles as $role)
                <option value="{{ $role->id }}"
                    {{ isset($bond) ? ($bond->role_id == $role->id ? 'selected' : '') : (old('role_id') == $role->id ? 'selected' : '') }}>
                    {{ $role->name }}</option>
            @endforeach
        </select>
        @error('role_id')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-12 col-sm-12 col-md-7 col-lg-6 col-xl-6 col-xxl-6">
        <label for="selectCourse1" class="form-label">Curso</label>
        <select name="course_id" id="selectCourse1" class="form-select searchable-select">
            <option value="">Não se aplica</option>
            @foreach ($courses as $course)
                <option value="{{ $course->id }}"
                    {{ isset($bond) ? ($bond->course?->id == $course->id ? 'selected' : '') : (old('course_id') == $course->id ? 'selected' : '') }}>
                    {{ $course->name }}</option>
            @endforeach
        </select>
        @error('course_id')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-12 col-sm-12 col-md-5 col-lg-6 col-xl-6 col-xl-6">
        <label for="selectPole1" class="form-label">Polo</label>
        <select name="pole_id" id="selectPole1" class="form-select searchable-select">
            <option value="">Não se aplica</option>
            @foreach ($poles as $pole)
                <option value="{{ $pole->id }}"
                    {{ isset($bond) ? ($bond->pole?->id == $pole->id ? 'selected' : '') : (old('pole_id') == $pole->id ? 'selected' : '') }}>
                    {{ $pole->name }}</option>
            @endforeach
        </select>
        @error('pole_id')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row g-3 mb-3">
    <div class="col-4 col-sm-4 col-md-3 col-lg-2">
        <label for="inputBegin1" class="form-label">Início*</label>
        <input type="date" name="begin" value="{{ isset($bond) ? $bond->begin : old('begin') }}" id="inputBegin1"
            class="form-control" required />
            @error('begin')
                <div class="text-danger">> {{ $message }}</div>
            @enderror
    </div>
    <div class="col-4 col-sm-4 col-md-3 col-lg-2">
        <label for="inputTerminated1" class="form-label">Terminado em</label>
        <input type="date" name="terminated_at" value="{{ isset($bond) ? $bond->terminated_at : old('terminated_at') }}" id="inputTerminated1"
            class="form-control">
            @error('terminated_at')
                <div class="text-danger">> {{ $message }}</div>
            @enderror
    </div>
    <div class="col-4 col-sm-4 col-md-2 col-lg-2">
        <label for="inputHiringProcess1" class="form-label">Edital*</label>
        <input name="hiring_process" id="inputHiringProcess1" type="text" class="form-control" placeholder="0000/0000"
            value="{{ isset($bond) ? $bond->hiring_process : old('hiring_process') }}" maxlength="9"
            data-inputmask="'mask': '(9/9999)|(99/9999)|(999/9999)|(9999/9999)', 'removeMaskOnSubmit': false"
            pattern="(([0-9]{1})?([0-9]{1})?([0-9]{1})?([0-9]{1})[\/]\d{4})" required />
        @error('hiring_process')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-12 col-md-4 d-flex align-items-end">
        <div class="form-check mt-md-10">
            <input type="checkbox" class="form-check-input" name="volunteer" id="inputVolunteer1"
                {{ isset($bond) ? ($bond->volunteer ? 'checked' : '') : (old('volunteer') ? 'checked' : '') }} />
            <label for="inputVolunteer1" class="form-label">Voluntário</label>
        </div>
    </div>
</div>
<div class="row g-3 mb-3">
    <div class="col-6 col-sm-6 col-md-4 col-lg-3">
        <label for="selectKnowledgeArea1" class="form-label">Área último Curso Superior*</label>
        <select name="qualification_knowledge_area" id="selectKnowledgeArea1" class="form-select">
            <option value="">Selecione a Área</option>
            @foreach ($knowledgeAreas as $knowledgeArea)
                <option value="{{ $knowledgeArea->name }}"
                    {{ isset($bond) && isset($bond->qualification) ? ($bond->qualification?->knowledge_area == $knowledgeArea->name ? 'selected' : '') : (old('qualification_knowledge_area') == $knowledgeArea->name ? 'selected' : '') }}>
                    {{ $knowledgeArea->label() }}</option>
            @endforeach
        </select>
        @error('qualification_knowledge_area')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-6 col-sm-6 col-md-4 col-lg-4">
        <label for="inputCourse1" class="form-label">Último curso de titulação*</label>
        <input name="qualification_course" id="inputCourse1" type="text" class="form-control" placeholder="Curso"
            value="{{ isset($bond) && isset($bond->qualification) ? $bond->qualification?->course_name : old('qualification_course') }}" maxlength="100" required />
        @error('qualification_course')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-12 col-sm-12 col-md-4 col-lg-5">
        <label for="inputInstitution1" class="form-label">Nome Instituição de Titulação*</label>
        <input name="qualification_institution" id="inputInstitution1" type="text" class="form-control" placeholder="Instituição"
            value="{{ isset($bond) && isset($bond->qualification) ? $bond->qualification?->institution_name : old('qualification_institution') }}" maxlength="100" required />
        @error('qualification_institution')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
</div>
    
