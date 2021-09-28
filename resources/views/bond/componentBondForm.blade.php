@csrf
<div class="row g-3 mb-3">
    <div class="col-12 col-lg-6">
        <label for="selectEmployee1" class="form-label">Colaborador*</label>
        <select name="employees" id="selectEmployee1" class="form-select">
            <option value="">Selecione o colaborador</option>
            @foreach ($employees as $employee)
                <option value="{{ $employee->id }}"
                    {{ isset($bond) ? ($bond->employee_id == $employee->id ? 'selected' : '') : (old('employees') == $employee->id ? 'selected' : '') }}>
                    {{ $employee->name . ' - ' . $employee->cpf }}</option>
            @endforeach
        </select>
        @error('employees')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-12 col-lg-6">
        <label for="selectRole1" class="form-label">Função*</label>
        <select name="roles" id="selectRole1" class="form-select">
            <option value="">Selecione a Função</option>
            @foreach ($roles as $role)
                <option value="{{ $role->id }}"
                    {{ isset($bond) ? ($bond->role_id == $role->id ? 'selected' : '') : (old('roles') == $role->id ? 'selected' : '') }}>
                    {{ $role->name }}</option>
            @endforeach
        </select>
        @error('roles')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-12 col-md-7">
        <label for="selectCourse1" class="form-label">Curso*</label>
        <select name="courses" id="selectCourse1" class="form-select">
            <option value="">Selecione o curso</option>
            @foreach ($courses as $course)
                <option value="{{ $course->id }}"
                    {{ isset($bond) ? ($bond->course_id == $course->id ? 'selected' : '') : (old('courses') == $course->id ? 'selected' : '') }}>
                    {{ $course->name }}</option>
            @endforeach
        </select>
        @error('courses')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-12 col-md-5">
        <label for="selectPole1" class="form-label">Polo*</label>
        <select name="poles" id="selectPole1" class="form-select">
            <option value="">Selecione o polo</option>
            @foreach ($poles as $pole)
                <option value="{{ $pole->id }}"
                    {{ isset($bond) ? ($bond->pole_id == $pole->id ? 'selected' : '') : (old('poles') == $pole->id ? 'selected' : '') }}>
                    {{ $pole->name }}</option>
            @endforeach
        </select>
        @error('poles')
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
