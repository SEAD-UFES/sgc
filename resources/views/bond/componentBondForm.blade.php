@csrf

Colaborador*: <select name="employees">
    <option value="">Selecione o colaborador</option>
    @foreach ($employees as $employee)
        <option value="{{ $employee->id }}" {{ $employee->id == $bond->employee_id ? 'selected' : '' }}>
            {{ $employee->name }}</option>
    @endforeach
</select>
@error('employees')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
Atribuição*: <select name="roles">
    <option value="">Selecione a atribuição</option>
    @foreach ($roles as $role)
        <option value="{{ $role->id }}" {{ $role->id == $bond->role_id ? 'selected' : '' }}>{{ $role->name }}
        </option>
    @endforeach
</select>
@error('roles')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
Curso*: <select name="courses">
    <option value="">Selecione o curso</option>
    @foreach ($courses as $course)
        <option value="{{ $course->id }}" {{ $course->id == $bond->course_id ? 'selected' : '' }}>
            {{ $course->name }}</option>
    @endforeach
</select>
@error('courses')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
Polo*: <select name="poles">
    <option value="">Selecione o polo</option>
    @foreach ($poles as $pole)
        <option value="{{ $pole->id }}" {{ $pole->id == $bond->pole_id ? 'selected' : '' }}>
            {{ $pole->name }}</option>
    @endforeach
</select>
@error('poles')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
Início: <input type="date" name="begin" value="{{ $bond->begin ?? old('begin') }}">
<br /><br />
Fim: <input type="date" name="end" value="{{ $bond->end ?? old('end') }}">
<br /><br />
Voluntário: <input type="checkbox" name="volunteer" {{ $bond->volunteer ? 'checked' : '' }} />
<br /><br />
