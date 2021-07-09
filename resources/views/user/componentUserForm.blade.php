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
Tipo de usuário*: <select name="userTypes">
    <option value="">Selecione um tipo</option>
    @foreach ($userTypes as $userType)
        <option value="{{ $userType->id }}" {{($userType->id == $user->user_type_id) ? 'selected' : ''}}>{{ $userType->name }}</option>
    @endforeach
</select>
@error('userTypes')
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
