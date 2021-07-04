@extends('layouts.basic')

@section('title', 'Listar Usuários')

@section('content')
    <section>
        <strong>Listar Usuários</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <table>
                <thead>
                    <th>E-mail</th>
                    <th>Atribuição</th>
                    <th>Ativo</th>
                    <th colspan="2">Ações</th>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->email }}</td>
                            <td>{{ $roles[($user->role_id - 1)]->name }}</td>
                            <td>{{ ($user->active === 1) ? 'Sim' : 'Não'}}</td>
                            <td>Editar</td>
                            <td>Excluir</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <br />
        </main>
    </section>
@endsection
