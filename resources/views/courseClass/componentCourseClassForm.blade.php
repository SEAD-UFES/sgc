@csrf
<div class="mb-3">
    <label for="inputName1" class="form-label">Nome*</label>
    <input name="name" type="text" id="inputName1" class="form-control" placeholder="Nome da Disciplina"
        value="{{ isset($courseClass) ? $courseClass->name : old('name') }}" />
    @error('name')
        <div class="text-danger">> {{ $message }}</div>
    @enderror
</div>
<div class="mb-3">
    <label for="selectCourse1" class="form-label">Curso*</label>
    <select name="course_id" id="selectCourse1" class="form-select">
        <option value="">Selecione o Curso</option>
        @foreach ($courses as $course)
            <option value="{{ $course->id }}"
                {{ isset($courseClass) ? ($courseClass->course_id == $course->id ? 'selected' : '') : (Request::get('givenCourse') != null ? (Request::get('givenCourse') == $course->id ? 'selected' : '') : (old('course_id') == $course->id ? 'selected' : '')) }}>
                {{ $course->name }}</option>
        @endforeach
    </select>
    @error('course_id')
        <div class="text-danger">> {{ $message }}</div>
    @enderror
</div>
<div class="mb-3">
    <label for="inputCode1" class="form-label">Código*</label>
    <input name="code" type="text" id="inputCode1" class="form-control" placeholder="Código da Disciplina"
        value="{{ isset($courseClass) ? $courseClass->name : old('code') }}" />
    @error('code')
        <div class="text-danger">> {{ $message }}</div>
    @enderror
</div>
<div class="mb-3">
    <label for="inputCpp1" class="form-label">PPC</label>
    <input name="cpp" type="text" id="inputCpp1" class="form-control" placeholder="PPC"
        value="{{ isset($courseClass) ? $courseClass->cpp : old('cpp') }}" />
    @error('cpp')
        <div class="text-danger">> {{ $message }}</div>
    @enderror
</div>
