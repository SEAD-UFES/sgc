@csrf
<div class="mb-3">
    <label for="selectEmployee1" class="form-label">Colaborador*</label>
    <select name="employees" id="selectEmployee1" class="form-select">
        <option value="">Selecione o colaborador</option>
        @foreach ($employees as $employee)
            <option value="{{ $employee->id }}" {{ $employee->id == $bond->employee_id ? 'selected' : '' }}>
                {{ $employee->name . ' - ' . $employee->cpf }}</option>
        @endforeach
    </select>
    @error('employees')
        <div class="error">> {{ $message }}</div>
    @enderror
</div>
<div class="mb-3">
    <label for="selectRole1" class="form-label">Atribuição*</label>
    <select name="roles" id="selectRole1" class="form-select">
        <option value="">Selecione a atribuição</option>
        @foreach ($roles as $role)
            <option value="{{ $role->id }}" {{ $role->id == $bond->role_id ? 'selected' : '' }}>
                {{ $role->name }}
            </option>
        @endforeach
    </select>
    @error('roles')
        <div class="error">> {{ $message }}</div>
    @enderror
</div>
<div class="mb-3">
    <label for="selectCourse1" class="form-label">Curso*</label>
    <select name="courses" id="selectCourse1" class="form-select">
        <option value="">Selecione o curso</option>
        @foreach ($courses as $course)
            <option value="{{ $course->id }}" {{ $course->id == $bond->course_id ? 'selected' : '' }}>
                {{ $course->name }}</option>
        @endforeach
    </select>
    @error('courses')
        <div class="error">> {{ $message }}</div>
    @enderror
</div>
<div class="mb-3">
    <label for="selectPole1" class="form-label">Polo*</label>
    <select name="poles" id="selectPole1" class="form-select">
        <option value="">Selecione o polo</option>
        @foreach ($poles as $pole)
            <option value="{{ $pole->id }}" {{ $pole->id == $bond->pole_id ? 'selected' : '' }}>
                {{ $pole->name }}</option>
        @endforeach
    </select>
    @error('poles')
        <div class="error">> {{ $message }}</div>
    @enderror
</div>
<div class="mb-3">
    <label for="inputBegin1" class="form-label">Início</label>
    <input type="date" name="begin" value="{{ $bond->begin ?? old('begin') }}" id="inputBegin1" class="form-control">
</div>
<div class="mb-3">
    <label for="inputEnd1" class="form-label">Fim</label>
    <input type="date" name="end" value="{{ $bond->end ?? old('end') }}" id="inputEnd1" class="form-control">
</div>

<div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input" name="volunteer" id="inputVolunteer1"
        {{ $bond->volunteer ? 'checked' : '' }} />
    <label for="inputVolunteer1" class="form-label">Voluntário</label>
</div>
