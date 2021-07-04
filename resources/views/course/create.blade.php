@extends('layouts.basic')

@section('title', 'Cadastrar Curso')

@section('content')
    <section>
        <strong>Cadastrar Curso</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <form action={{ route('course.store') }} method="POST">
                @csrf
                Nome*: <input name="name" type="text" placeholder="Nome do Curso" value="{{ old('name') }}" />
                @error('name')
                    <div class="error">> {{ $message }}</div>
                @enderror
                <br /><br />
                Descrição: <input name="description" type="text" placeholder="Descrição do curso"/>
                @error('description')
                    <div class="error">> {{ $message }}</div>
                @enderror
                <br /><br />
                Início: <input type="date" name="begin">
                <br /><br />
                Início: <input type="date" name="end">
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
