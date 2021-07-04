@extends('layouts.basic')

@section('title', 'Cadastrar Atribuição')

@section('content')
    <section>
        <strong>Cadastrar Atribuição</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <form action={{ route('role.store') }} method="POST">
                @csrf
                Nome*: <input name="name" type="text" placeholder="Nome da atribuição" value="{{ old('email') }}" />
                @error('name')
                    <div class="error">> {{ $message }}</div>
                @enderror
                <br /><br />
                Descrição*: <input name="description" type="text" placeholder="Descrição" />
                @error('description')
                    <div class="error">> {{ $message }}</div>
                @enderror
                <br /><br />
                Valor da bolsa*: <input name="grantValue" type="number" placeholder="0" />
                @error('grantValue')
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
