@extends('layouts.basic')

@section('title', 'Importar Aprovados')

@section('content')
    <section>
        <h2>Importar Aprovados</h2>
    </section>
    <section id="pageContent">
        <main role="main">
            <form action={{ route('approveds.import') }} method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="inputFile1" class="form-label">Selecione o arquivo</label>
                    <input class="form-control" type="file" name="file" id="inputFile1">
                </div>
                <br />
                <button type="submit" class="btn btn-primary">Enviar arquivo</button> <button type="button"
                    onclick="history.back()" class="btn btn-secondary">Cancelar</button>
                @error('noStore')
                    <div class="error">> {{ $message }}</div>
                @enderror
            </form>
            <br />
            <p>Formato mínimo esperado da planilha: Nome, Email, Telefone(s), Edital</p>
            <p>Ex:</p>
            <table class="table table-striped table-hover">
                <tr>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Telefone</th>
                    <th>Edital</th>
                </tr>
                <tr>
                    <td>Mateus</td>
                    <td>mateus@ufes.br</td>
                    <td>(27) 3339 3554<br />(27) 99999-9375</td>
                    <td>099/2021</td>
                </tr>
                <tr>
                    <td>Júlia</td>
                    <td>julia@gmail.com</td>
                    <td>(27) 99999-9374</td>
                    <td>099/2021</td>
                </tr>
                <tr>
                    <td>Gabriel</td>
                    <td>gabriel@outlook.com</td>
                    <td>(28) 99999-0647</td>
                    <td>099/2021</td>
                </tr>
            </table>
            <br />
        </main>
    </section>
@endsection
