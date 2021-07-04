@extends('layouts.basic')

@section('title', 'Listar Colaboradores')

@section('content')
    <section>
        <strong>Listar Colaboradores</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <nav>
                <ul>
                    <li><a href="{{ route('employee.create') }}">Cadastrar Colaborador</a></li>
                </ul>
            </nav>
            <br /><br />
            <table>
                <thead>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Email</th>
                    <th colspan="2">Ações</th>
                </thead>
                <tbody>
                    @foreach ($employees as $employee)
                        <tr>
                            <td>{{ $employee->name }}</td>
                            <td>{{ $employee->cpf }}</td>
                            <td>{{ $employee->email }}</td>
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
