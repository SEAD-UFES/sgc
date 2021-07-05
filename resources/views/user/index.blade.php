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
                            <td>{{ $roles[$user->role_id - 1]->name }}</td> {{-- How to make it better? --}}
                            <td>{{ $user->active === 1 ? 'Sim' : 'Não' }}</td>
                            <td><a href="{{ route('user.edit', $user) }}">Editar</a></td>
                            {{-- <td><a href="{{ route('user.destroy', $user) }}">Excluir</a></td> --}}
                            <td><a onClick="{{ 'if(confirm("Tem certeza que deseja excluir esse usuário?")) window.location.replace(\'' . route('user.destroy', $user) . '\')' }}" style="cursor:pointer; color:blue; text-decoration:underline;">Excluir</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <br />
        </main>
    </section>
@endsection
