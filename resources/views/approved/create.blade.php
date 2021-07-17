@extends('layouts.basic')

@section('title', 'Importar Aprovados')

@section('content')
    <section>
        <strong>Importar Aprovados</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <form action={{ route('approveds.import') }} method="POST" enctype="multipart/form-data">
                @csrf
                Selecione o arquivo: <input type="file" name="file">
                <br /><br />
                <button type="submit">Enviar arquivo</button> <button type="button"
                    onclick="history.back()">Cancelar</button>
                @error('noStore')
                    <div class="error">> {{ $message }}</div>
                @enderror
                <br /><br /><br />
            </form>
            <br />
            <p style="text-align: center">Formato mínimo esperado da planilha:</p><br />
            <table border=0 cellpadding=0 cellspacing=0 width=390 style='border-collapse:
            collapse;table-layout:fixed;width:400pt'>
                <tr>
                    <th style="width:60pt">Nome</th>
                    <th style="width:130pt">E-mail</th>
                    <th style="width:130pt">Telefone</th>
                    <th style="width:80pt">Edital</th>
                </tr>
                <tr>
                    <td>&nbsp;Mateus</td>
                    <td>&nbsp;mateus@ufes.br</td>
                    <td>&nbsp;(27) 99999 3554</td>
                    <td>&nbsp;099/2021</td>
                </tr>
                <tr>
                    <td>&nbsp;Júlia</td>
                    <td>&nbsp;julia@gmail.com</td>
                    <td>&nbsp;(27) 99999-9374</td>
                    <td>&nbsp;099/2021</td>
                </tr>
                <tr>
                    <td>&nbsp;Gabriel</td>
                    <td>&nbsp;gabriel@outlook.com</td>
                    <td>&nbsp;(28) 99999-0647</td>
                    <td>&nbsp;099/2021</td>
                </tr>
            </table>
            <br />
        </main>
    </section>
@endsection
