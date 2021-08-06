@extends('layouts.basic')

@section('title', 'Login')

@section('content')
    <section>
        <h2>Login</h2>
    </section>
    <section id="pageContent">
        <main role="main">
            <form action={{ route('auth.login') }} method="POST">
                @csrf
                E-Mail*: <input name="email" type="text" placeholder="Email" value="{{ old('email') }}" />
                @error('email')
                    <div class="error">&gt; {{ $message }}</div>
                @enderror
                <br /><br />
                Senha*: <input name="password" type="password" placeholder="Senha" />
                @error('password')
                    <div class="error">&gt; {{ $message }}</div>
                @enderror
                <br /><br />
                <button type="submit">Login</button>
                @error('noAuth')
                    <div class="error">&gt; {{ $message }}</div>
                @enderror
                <br /><br />
            </form>
            <code>
                Usuário de teste: admin@ufes.br<br />
                Senha: senha123
            </code><br /><br />
        </main>
    </section>
@endsection
