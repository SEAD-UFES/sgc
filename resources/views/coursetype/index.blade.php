@extends('layouts.basic')

@section('title', 'Tipos de Cursos')

@section('content')
    <section>
        <h2>Tipos de Cursos</h2>
    </section>
    <section id="pageContent">
        <main role="main">
            <table class="table table-striped table-hover">
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
            <button type="button" onclick="history.back()" class="btn btn-secondary">Voltar</button>
        </main>
    </section>
@endsection
