@extends('layouts.basic')

@section('title', 'Cadastrar Usuário')

@section('content')
    <section>
        <strong>Cadastrar Usuário</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <form action={{ route('user.store') }} method="POST">
                @csrf
                E-Mail*: <input name="email" type="email" placeholder="nome@empresa.com" value="{{ old('email') }}" />
                @error('email')
                    <div class="error">> {{ $message }}</div>
                @enderror
                <br /><br />
                Senha*: <input name="password" type="password" placeholder="Senha" />
                @error('password')
                    <div class="error">> {{ $message }}</div>
                @enderror
                <br /><br />
                Atribuição*: <select name="roles">
                    <option value="1">Selecione um atribuição</option>
                </select>
                <br /><br />
                Ativo: <input type="checkbox" name="active" />
                <br /><br />
                <button type="submit">Cadastrar</button>
                @error('noStore')
                    <div class="error">> {{ $message }}</div>
                @enderror
                <br /><br />
            </form>
        </main>
    </section>
@endsection
