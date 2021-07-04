@extends('layouts.basic')

@section('title', 'Listar Atribuições')

@section('content')
    <section>
        <strong>Listar Atribuições</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <table>
                <thead>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Valor da Bolsa</th>
                    <th colspan="2">Ações</th>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td>{{ $role->name }}</td>
                            <td>{{ $role->description }}</td>
                            <td>{{ $role->grant_value }}</td>
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
