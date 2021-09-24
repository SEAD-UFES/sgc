@csrf
<div class="mb-3">
    <label for="inputEmail1" class="form-label">E-Mail*</label>
    <input name="email" type="email" autocomplete="username" id="inputEmail1" class="form-control"
        placeholder="nome@empresa.com" value="{{ isset($user) ? $user->email : old('email') }}" />
    @error('email')
        <div class="text-danger">> {{ $message }}</div>
    @enderror
</div>
<div class="mb-3">
    <label for="inputPassword1" class="form-label">Nova Senha</label>
    <input name="password" type="password" autocomplete="new-password" id="inputPassword1" class="form-control"
        placeholder="Nova Senha" />
    @error('password')
        <div class="text-danger">> {{ $message }}</div>
    @enderror
</div>
<div class="mb-3" class="form-check">
    <input type="checkbox" class="form-check-input" name="active" id="inputActive1"
        {{ isset($user) ? ($user->active ? 'checked' : '') : (old('active') ? 'checked' : '') }} />
    <label for="inputActive1" class="form-label">Ativo</label>
</div>
