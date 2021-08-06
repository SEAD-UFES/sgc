@csrf
<div class="mb-3">
    <label for="inputName1" class="form-label">Nome*</label>
    <input name="name" type="text" id="inputName1" class="form-control" placeholder="Nome da atribuição"
        value="{{ $role->name ?? old('name') }}" />
    @error('name')
        <div class="error">> {{ $message }}</div>
    @enderror
</div>
<div class="mb-3">
    <label for="inputDescription1" class="form-label">Descrição</label>
    <input name="description" type="text" id="inputDescription1" class="form-control" placeholder="Descrição"
        value="{{ $role->description ?? old('description') }}" />
    @error('description')
        <div class="error">> {{ $message }}</div>
    @enderror
</div>
<div class="mb-3">
    <label for="inputValue1" class="form-label">Valor da bolsa</label>
    <input name="grantValue" type="text" id="inputValue1" class="form-control" placeholder="R$"
        value="{{ $role->grant_value ?? old('grantValue') }}" />
    @error('grantValue')
        <div class="error">> {{ $message }}</div>
    @enderror
</div>
<div class="mb-3">
    <label for="selectType1" class="form-label">Tipo*</label>
    <select name="grantTypes" id="selectType1" class="form-select">
        <option value="">Selecione o tipo</option>
        @foreach ($grantTypes as $grantType)
            <option value="{{ $grantType->id }}" {{ $grantType->id == $role->grant_type_id ? 'selected' : '' }}>
                {{ $grantType->name }}</option>
        @endforeach
    </select>
    @error('grantTypes')
        <div class="error">> {{ $message }}</div>
    @enderror
</div>
