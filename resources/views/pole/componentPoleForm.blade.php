@csrf
Nome*: <input name="name" type="text" placeholder="Nome do Polo" value="{{ $pole->name ?? old('name') }}" />
@error('name')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
Descrição: <input name="description" type="text" placeholder="Descrição" value="{{ $pole->description ?? old('description') }}" />
@error('description')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
