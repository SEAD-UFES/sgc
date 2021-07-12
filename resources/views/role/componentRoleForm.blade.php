@csrf
Nome*: <input name="name" type="text" placeholder="Nome da atribuição" value="{{ $role->name ?? old('name') }}" />
@error('name')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
Descrição: <input name="description" type="text" placeholder="Descrição" value="{{ $role->description ?? old('description') }}" />
@error('description')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
Valor da bolsa: <input name="grantValue" type="text" placeholder="R$" value="{{ $role->grant_value ?? old('grantValue') }}" />
@error('grantValue')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
Tipo*: <select name="grantTypes">
    <option value="">Selecione o tipo</option>
    @foreach ($grantTypes as $grantType)
        <option value="{{ $grantType->id }}" {{($grantType->id == $role->grant_type_id) ? 'selected' : ''}}>{{ $grantType->name }}</option>
    @endforeach
</select>
@error('grantTypes')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />