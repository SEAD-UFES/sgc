@extends('layouts.basic')

@section('title', 'Listar Cursos')

@section('content')
    <section>
        <strong>Listar Cursos</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <table>
                <thead>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Início</th>
                    <th>Fim</th>
                    <th colspan="2">Ações</th>
                </thead>
                <tbody>
                    @foreach ($courses as $course)
                        <tr>
                            <td>{{ $course->name }}</td>
                            <td>{{ $course->description }}</td>
                            <td>{{ $course->begin }}</td>
                            <td>{{ $course->end }}</td>
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
