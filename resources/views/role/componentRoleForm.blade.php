@vite('resources/js/enable_custom_inputmask.ts')
@csrf

<div class="row g-3 mb-3">
    <div class="col-12">
        <label for="inputName1" class="form-label">Nome*</label>
        <input name="name" type="text" id="inputName1" class="form-control" placeholder="Nome da Função"
            value="{{ isset($role) ? $role->name : old('name') }}" />
        @error('name')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-12">
        <label for="inputDescription1" class="form-label">Descrição</label>
        <input name="description" type="text" id="inputDescription1" class="form-control" placeholder="Descrição"
            value="{{ isset($role) ? $role->description : old('description') }}" />
        @error('description')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-6">
        <label for="inputValue1" class="form-label">Valor da bolsa</label>
        <input name="grant_value" type="text" id="inputValue1" class="form-control" placeholder="R$"
            value="{{ isset($role) ? numfmt_format_currency(numfmt_create('pt_BR', NumberFormatter::CURRENCY), $role->grantValue , 'BRL') : old('grant_value') }}" />
        @error('grant_value')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-6">
        <label for="selectType1" class="form-label">Tipo*</label>
        <select name="grant_type" id="selectType1" class="form-select">
            <option value="">Selecione o tipo</option>
            @foreach ($grantTypes as $grantType)
                <option value="{{ $grantType->name }}"
                    {{ isset($role) ? ($role->grant_type == $grantType ? 'selected' : '') : (old('grant_type') == $grantType->name ? 'selected' : '') }}>
                    {{ $grantType->label() }}</option>
            @endforeach
        </select>
        @error('grant_type')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
</div>
