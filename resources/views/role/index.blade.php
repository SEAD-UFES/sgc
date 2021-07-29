@extends('layouts.basic')

@section('title', 'Listar Atribuições')

@section('content')
    <section>
        <strong>Listar Atribuições</strong>
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
                    <th>@sortablelink('name', 'Nome')</th>
                    <th>@sortablelink('description', 'Descrição')</th>
                    <th>@sortablelink('grant_value', 'Valor da Bolsa')</th>
                    <th>@sortablelink('grantType.name', 'Tipo da Bolsa')</th>
                    <th colspan="2">Ações</th>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td>{{ $role->name }}</td>
                            <td>{{ $role->description }}</td>
                            <td>{{ numfmt_format_currency(numfmt_create('pt_BR', NumberFormatter::CURRENCY), $role->grant_value, 'BRL') }}</td>
                            <td>{{ $role->grantType->name }}</td>
                            <td><a href="{{ route('roles.edit', $role) }}">Editar</a></td>
                            <td>
                                <form name="{{ 'formDelete' . $role->id }}" action="{{ route('roles.destroy', $role) }}"
                                    method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <span
                                        onclick="{{ 'if(confirm(\'Tem certeza que deseja excluir essa Atribuição?\')) document.forms[\'formDelete' . $role->id . '\'].submit();' }}"
                                        style="cursor:pointer; color:blue; text-decoration:underline;">Excluir</span>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {!! $roles->links() !!}
            <br />
        </main>
    </section>
@endsection
