@csrf
E-Mail*: <input name="email" type="email" placeholder="nome@empresa.com" value="{{ $user->email ?? old('email') }}" />
@error('email')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
Nova Senha: <input name="password" type="password" placeholder="Nova Senha" />
@error('password')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
Atribuição*: <select name="roles">
    <option value="">Selecione um atribuição</option>
    @foreach ($roles as $role)
        <option value="{{ $role->id }}" {{($role->id == $user->role_id) ? 'selected' : ''}}>{{ $role->name }}</option>
    @endforeach
</select>
@error('roles')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
Ativo: <input type="checkbox" name="active" {{($user->active) ? 'checked' : ''}}/>
<br /><br />
<button type="submit">Cadastrar</button>
@error('noStore')
    <div class="error">> {{ $message }}</div>
@enderror
<br /><br />
