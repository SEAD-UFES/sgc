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
        </main>
    </section>
@endsection
