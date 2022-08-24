@csrf

<div class="row g-3 mb-3">
    <div class="col-12">
        <label for="inputName1" class="form-label">Nome*</label>
        <input name="name" type="text" id="inputName1" class="form-control" placeholder="Nome do Curso"
            value="{{ isset($course) ? $course->name : old('name') }}" />
        @error('name')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-12">
        <label for="inputDescription1" class="form-label">Descrição</label>
        <input name="description" type="text" id="inputDescription1" class="form-control"
            placeholder="Descrição do curso"
            value="{{ isset($course) ? $course->description : old('description') }}" />
        @error('description')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-12 col-md-6">
        <label for="selectType1" class="form-label">Tipo*</label>
        <select name="course_type_id" id="selectType1" class="form-select">
            <option value="">Selecione o tipo</option>
            @foreach ($courseTypes as $courseType)
                <option value="{{ $courseType->id }}"
                    {{ isset($course) ? ($course->course_type_id == $courseType->id ? 'selected' : '') : (old('course_type_id') == $courseType->id ? 'selected' : '') }}>
                    {{ $courseType->name }}
                </option>
            @endforeach
        </select>
        @error('course_type_id')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-6 col-md-3">
        <label for="inputBegin1" class="form-label">Início</label>
        <input type="date" name="begin" id="inputBegin1" class="form-control"
            value="{{ isset($course) ? $course->begin : old('begin') }}">
    </div>
    <div class="col-6 col-md-3">
        <label for="inputEnd1" class="form-label">Fim</label>
        <input type="date" name="end" id="inputEnd1" class="form-control"
            value="{{ isset($course) ? $course->end : old('end') }}">
    </div>
    <div class="col-12">
        <label for="inputLmsUrl1" class="form-label">Endereço do AVA:</label>
        <input name="lms_url" type="text" id="inputLmsUrl1" class="form-control" placeholder="Endereço do AVA"
            value="{{ isset($course) ? $course->lms_url : old('lms_url') }}" />
        @error('lms_url')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
</div>
