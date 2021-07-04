@extends('layouts.basic')

@section('title', 'Cadastrar Funcionário')

@section('content')
    <section>
        <strong>Cadastrar Funcionário</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <form action={{ route('employee.store') }} method="POST">
                @csrf
                Nome*: <input name="name" type="text" placeholder="Nome do Colaborador" value="{{ old('name') }}" />
                @error('name')
                    <div class="error">> {{ $message }}</div>
                @enderror
                <br /><br />
                CPF*: <input name="cpf" type="text" placeholder="11122233344"/>
                @error('cpf')
                    <div class="error">> {{ $message }}</div>
                @enderror
                <br /><br />
                E-mail: <input name="email" type="email" placeholder="pessoa@empresa.com"/>
                @error('email')
                    <div class="error">> {{ $message }}</div>
                @enderror
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
