@extends('layouts.basic')

@section('title', 'Listar Colaboradores')

@section('content')
    <section>
        <strong>Listar Colaboradores</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <table>
                <thead>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th colspan="2">Ações</th>
                </thead>
                <tbody>
                    @foreach ($employees as $employee)
                        <tr>
                            <td>{{ $employee->name }}</td>
                            <td>{{ $employee->cfp }}</td>
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
