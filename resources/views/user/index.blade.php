@extends('layouts.basic')

@section('title', 'Listar Usuários')

@section('content')
    <section>
        <strong>Listar Usuários</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p style="color: green; font-weight: bold">{{ $message }}</p>
                </div><br />
            @endif
            <table>
                <thead>
                    <th>E-mail</th>
                    <th>Tipo</th>
                    <th>Ativo</th>
                    <th>Colaborador</th>
                    <th colspan="2">Ações</th>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->userType->name }}</td>
                            <td>{{ $user->active === 1 ? 'Sim' : 'Não' }}</td>
                            <td>{{ $user->employee_id }}</td>
                            <td><a href="{{ route('users.edit', $user) }}">Editar</a></td>
                            <td>
                                <form name="{{ 'formDelete' . $user->id }}" action="{{ route('users.destroy', $user) }}"
                                    method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <span
                                        onclick="{{ 'if(confirm(\'Tem certeza que deseja excluir esse usuário?\')) document.forms[\'formDelete' . $user->id . '\'].submit();' }}"
                                        style="cursor:pointer; color:blue; text-decoration:underline;">Excluir</span>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {!! $users->links() !!}
            <br />
        </main>
    </section>
@endsection
