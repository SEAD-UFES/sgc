<script>
    $(document).ready(function(){
        Inputmask("decimal", { 
            onUnMask: function(maskedValue, unmaskedValue) {
                return parseInt(unmaskedValue);
            },
            "groupSeparator": ".",
            "autoGroup": true,
            "radixPoint": ",",
            "digits": "2",
            "digitsOptional": false,
            "prefix": "R$ ",
            "placeholder": "0",
            "removeMaskOnSubmit": true
        }).mask(document.getElementById("inputValue1"));
    });
</script>

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
            value="{{ isset($role) ? numfmt_format_currency(numfmt_create('pt_BR', NumberFormatter::CURRENCY), $role->grantValueReal , 'BRL') : old('grant_value') }}" />
        @error('grant_value')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
    <div class="col-6">
        <label for="selectType1" class="form-label">Tipo*</label>
        <select name="grant_type_id" id="selectType1" class="form-select">
            <option value="">Selecione o tipo</option>
            @foreach ($grantTypes as $grantType)
                <option value="{{ $grantType->id }}"
                    {{ isset($role) ? ($role->grant_type_id == $grantType->id ? 'selected' : '') : (old('grant_type_id') == $grantType->id ? 'selected' : '') }}>
                    {{ $grantType->name }}</option>
            @endforeach
        </select>
        @error('grant_type_id')
            <div class="text-danger">> {{ $message }}</div>
        @enderror
    </div>
</div>
