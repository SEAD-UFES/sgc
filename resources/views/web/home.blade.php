@extends('layouts.basic')

@section('title', 'Home')

@section('content')
    <section>
        <strong>Home&nbsp;
            @if (session('sessionUser')->currentBond != null)
                [{{ session('sessionUser')->currentBond->role->name }} -
                {{ session('sessionUser')->currentBond->course->name }} -
                {{ session('sessionUser')->currentBond->pole->name }}]
            @endif

        </strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <h3>Mensagens</h3>
            <br />
            <table>
                <thead>
                    <tr>
                        <th>Destino</th>
                        <th>Assunto</th>
                        <th>Ação</th>
                </thead>
                <tbody>
                    <tr>
                        <td>Admin</td>
                        <td>Acesso indevido</td>
                        <td>[Ver mensagem]</td>
                    </tr>
                    <tr>
                        <td>Coordenador</td>
                        <td>Vínculo UAB Impedido</td>
                        <td>[Ver mensagem]</td>
                    </tr>
                    <tr>
                        <td>LDI</td>
                        <td>Novos Termos de Imagem</td>
                        <td>[Ver mensagem]</td>
                    </tr>
                </tbody>
            </table>
            <br />
        </main>
    </section>
@endsection
