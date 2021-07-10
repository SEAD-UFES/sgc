@csrf
Nome*: <input name="name" type="text" placeholder="Nome do Curso" value="{{ $course->name ?? old('name') }}" />
@error('name')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
Descrição: <input name="description" type="text" placeholder="Descrição do curso" value="{{ $course->description ?? old('description') }}" />
@error('description')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
Tipo*: <select name="courseTypes">
    <option value="">Selecione o tipo</option>
    @foreach ($courseTypes as $courseType)
        <option value="{{ $courseType->id }}" {{($courseType->id == $course->course_type_id) ? 'selected' : ''}}>{{ $courseType->name }}</option>
    @endforeach
</select>
@error('courseTypes')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
Início: <input type="date" name="begin" value="{{ $course->begin ?? old('begin') }}">
<br /><br />
Fim: <input type="date" name="end" value="{{ $course->end ?? old('end') }}">
<br /><br />
<button type="submit">Cadastrar</button>
@error('noStore')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
