@csrf
<div class="mb-3">
    <label for="selectEmployee1" class="form-label">Usuário*</label>
    <select name="user_id" id="selectEmployee1" class="form-select searchable-select">
        <option value="">Selecione o usuário</option>
        @foreach ($users as $user)
            <option value="{{ $user->id }}"
                {{ isset($responsibility) ? ($responsibility->user_id == $user->id ? 'selected' : '') : (old('user_id') == $user->id ? 'selected' : '') }}>
                {{ $user->email }}</option>
        @endforeach
    </select>
    @error('user_id')
        <div class="text-danger">> {{ $message }}</div>
    @enderror
</div>
<div class="mb-3">
    <label for="selectRole1" class="form-label">Papel*</label>
    <select name="user_type_id" id="selectRole1" class="form-select">
        <option value="">Selecione o papel</option>
        @foreach ($userTypes as $userType)
            <option value="{{ $userType->id }}"
                {{ isset($responsibility) ? ($responsibility->user_type_id == $userType->id ? 'selected' : '') : (old('user_type_id') == $userType->id ? 'selected' : '') }}>
                {{ $userType->name }}</option>
        @endforeach
    </select>
    @error('user_type_id')
        <div class="text-danger">> {{ $message }}</div>
    @enderror
</div>
<div class="mb-3">
    <label for="selectCourse1" class="form-label">Curso</label>
    <select name="course_id" id="selectCourse1" class="form-select searchable-select">
        <option value="">Sem curso específico</option>
        @foreach ($courses as $course)
            <option value="{{ $course->id }}"
                {{ isset($responsibility) ? ($responsibility->course_id == $course->id ? 'selected' : '') : (old('course_id') == $course->id ? 'selected' : '') }}>
                {{ $course->name }}</option>
        @endforeach
    </select>
    @error('course_id')
        <div class="text-danger">> {{ $message }}</div>
    @enderror
</div>
<div class="mb-3">
    <label for="inputBegin1" class="form-label">Início*</label>
    <input type="date" name="begin"
        value="{{ isset($responsibility) ? $responsibility->begin : old('begin') ?? date('Y-m-d') }}"
        id="inputBegin1" class="form-control">
    @error('begin')
        <div class="text-danger">> {{ $message }}</div>
    @enderror
</div>
<div class="mb-3">
    <label for="inputEnd1" class="form-label">Fim (Opcional)</label>
    <input type="date" name="end" value="{{ isset($responsibility) ? $responsibility->end : old('end') }}"
        id="inputEnd1" class="form-control">
    @error('end')
        <div class="text-danger">> {{ $message }}</div>
    @enderror
</div>
