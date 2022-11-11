@extends('layouts.basic')

@section('title', 'Login')

@section('content')
    {{-- <section>
        <h2>Login</h2>
    </section> --}}
    <section id="pageContent">
        <main role="main">
            <div class="row justify-content-center">
                <div class="col-sm-8 col-md-6 col-lg-5 col-xl-4 col-xxl-3">
                    <div class="border border-2 rounded-3 my-3 p-3">
                        <form action={{ route('auth.login') }} method="POST">
                            <h3>Login</h3>
                            @csrf
                            <div class="mb-3">
                                <label for="inputLogin1" class="form-label">Login*</label>
                                <input name="login" type="text" autocomplete="username" id="inputLogin1" class="form-control" placeholder="Login"
                                    value="{{ old('login') }}" />
                                @error('login')
                                    <div class="text-danger">&gt; {{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="inputSenha1" class="form-label">Senha*</label>
                                <input name="password" type="password" autocomplete="current-password" id="inputSenha1" class="form-control"
                                    placeholder="Senha" />
                                @error('password')
                                    <div class="text-danger">&gt; {{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Login</button>
                            @error('noAuth')
                                <div class="text-danger">&gt; {{ $message }}</div>
                            @enderror
                        </form>
                        @if (Illuminate\Support\Facades\App::class::Environment() != 'production')
                            <br /><br />
                            <code>
                                Usuário de teste: admin@ufes.br<br />
                                Senha: changeme
                            </code>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </section>
@endsection
