@csrf
<div class="row g-3 mb-3">
    <div class="col-12 col-lg-6">
        <label for="selectEmployee1" class="form-label">Colaborador*</label>
        <select name="employee_id" id="selectEmployee1" class="form-select">
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
    <div class="col-12 col-md-7">
        <label for="selectCourse1" class="form-label">Curso*</label>
        <select name="course_id" id="selectCourse1" class="form-select">
            <option value="">Selecione o curso</option>
            @foreach ($courses as $course)
                <option value="{{ $course->id }}"
                    {{ isset($bond) ? ($bond->course_id == $course->id ? 'selected' : '') : (old('course_id') == $course->id ? 'selected' : '') }}>
                    {{ $course->name }}</option>
            @endforeach
        </select>
        @error('course_id')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-12 col-md-5">
        <label for="selectPole1" class="form-label">Polo*</label>
        <select name="pole_id" id="selectPole1" class="form-select">
            <option value="">Selecione o polo</option>
            @foreach ($poles as $pole)
                <option value="{{ $pole->id }}"
                    {{ isset($bond) ? ($bond->pole_id == $pole->id ? 'selected' : '') : (old('pole_id') == $pole->id ? 'selected' : '') }}>
                    {{ $pole->name }}</option>
            @endforeach
        </select>
        @error('pole_id')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-6 col-md-3">
        <label for="inputBegin1" class="form-label">Início</label>
        <input type="date" name="begin" value="{{ isset($bond) ? $bond->begin : old('begin') }}" id="inputBegin1"
            class="form-control">
    </div>
    <div class="col-6 col-md-3">
        <label for="inputEnd1" class="form-label">Fim</label>
        <input type="date" name="end" value="{{ isset($bond) ? $bond->end : old('end') }}" id="inputEnd1"
            class="form-control">
    </div>
    <div class="col-12 col-md-12">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" name="volunteer" id="inputVolunteer1"
                {{ isset($bond) ? ($bond->volunteer ? 'checked' : '') : (old('volunteer') ? 'checked' : '') }} />
            <label for="inputVolunteer1" class="form-label">Voluntário</label>
        </div>
    </div>
</div>
<div class="row g-3 mb-3">
    <div class="col-6 col-sm-6 col-md-4 col-lg-3">
        <label for="selectKnowledgeArea1" class="form-label">Área do último Curso Superior</label>
        <select name="knowledge_area" id="selectKnowledgeArea1" class="form-select">
            <option value="">Selecione a Área</option>
            @foreach ($knowledgeAreas as $knowledgeArea)
                <option value="{{ $knowledgeArea }}"
                    {{ isset($bond) && isset($bond->qualification) ? ($bond->qualification->knowledge_area?->value == $knowledgeArea ? 'selected' : '') : (old('knowledge_area') == $knowledgeArea ? 'selected' : '') }}>
                    {{ $knowledgeArea }}</option>
            @endforeach
        </select>
        @error('knowledge_area')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-6 col-sm-6 col-md-4 col-lg-4">
        <label for="inputCourse1" class="form-label">Último curso de titulação</label>
        <input name="course_name" id="inputCourse1" type="text" class="form-control" placeholder="Curso"
            value="{{ isset($bond) && isset($bond->qualification) ? $bond->qualification->course_name : old('course_name') }}" maxlength="100" />
        @error('course_name')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-12 col-sm-12 col-md-4 col-lg-5">
        <label for="inputInstitution1" class="form-label">Nome da Instituição de Titulação</label>
        <input name="institution_name" id="inputInstitution1" type="text" class="form-control" placeholder="Instituição"
            value="{{ isset($bond) && isset($bond->qualification) ? $bond->qualification->institution_name : old('institution_name') }}" maxlength="100" />
        @error('institution_name')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
</div>
    
