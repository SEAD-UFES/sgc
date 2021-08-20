@csrf
<div class="mb-3">
    <label for="inputEmail1" class="form-label">E-Mail*</label>
    <input name="email" type="email" autocomplete="username" id="inputEmail1" class="form-control" placeholder="nome@empresa.com"
        value="{{ $user->email ?? old('email') }}" />
    @error('email')
        <div class="text-danger">> {{ $message }}</div>
    @enderror
</div>
<div class="mb-3">
    <label for="inputPassword1" class="form-label">Nova Senha</label>
    <input name="password" type="password" autocomplete="new-password" id="inputPassword1" class="form-control" placeholder="Nova Senha" />
    @error('password')
        <div class="text-danger">> {{ $message }}</div>
    @enderror
</div>
{{-- <div class="mb-3">
    <label for="selectType1" class="form-label">Tipo de usuário*</label>
    <select name="userTypes" id="selectType1" class="form-select">
        <option value="">Selecione o tipo</option>
        @foreach ($userTypes as $userType)
            <option value="{{ $userType->id }}" {{ $userType->id == $user->user_type_id ? 'selected' : '' }}>
                {{ $userType->name }}</option>
        @endforeach
    </select>
    @error('userTypes')
        <div class="text-danger">> {{ $message }}</div>
    @enderror
</div> --}}
<div class="mb-3" class="form-check">
    <input type="checkbox" class="form-check-input" name="active" id="inputActive1"
        {{ $user->active ? 'checked' : '' }} />
    <label for="inputActive1" class="form-label">Ativo</label>
</div>
