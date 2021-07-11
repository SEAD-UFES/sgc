@extends('layouts.basic')

@section('title', 'Listar Tipos de Cursos')

@section('content')
    <section>
        <strong>Listar Tipos de Cursos</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <table>
                <thead>
                    <th>Nome</th>
                    <th>Descrição</th>
                </thead>
                <tbody>
                    @foreach ($courseTypes as $courseType)
                        <tr>
                            <td>{{ $courseType->name }}</td>
                            <td>{{ $courseType->description }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <br />
            <button type="button" onclick="history.back()">Voltar</button>
            <br /><br />
        </main>
    </section>
@endsection
