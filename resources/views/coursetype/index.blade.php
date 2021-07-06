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
                    @foreach ($coursetypes as $coursetype)
                        <tr>
                            <td>{{ $coursetype->name }}</td>
                            <td>{{ $coursetype->description }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <br />
        </main>
    </section>
@endsection
