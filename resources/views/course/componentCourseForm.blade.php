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
        <label for="selectDegree1" class="form-label">Tipo*</label>
        <select name="degree" id="selectDegree1" class="form-select">
            <option value="">Selecione o tipo</option>
            @foreach ($degrees as $degree)
                <option value="{{ $degree->name }}"
                    {{ isset($course) ? ($course->degree == $degree ? 'selected' : '') : (old('degree') == $degree->name ? 'selected' : '') }}>
                    {{ $degree->label() }}
                </option>
            @endforeach
        </select>
        @error('degree')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-12">
        <label for="inputLmsUrl1" class="form-label">Endereço do AVA:</label>
        <input name="lms_url" type="url" id="inputLmsUrl1" class="form-control" placeholder="Endereço do AVA"
            value="{{ isset($course) ? $course->lms_url : old('lms_url') }}" />
        @error('lms_url')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
</div>
