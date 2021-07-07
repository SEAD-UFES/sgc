@extends('layouts.basic')

@section('title', 'Listar Cursos')

@section('content')
    <section>
        <strong>Listar Cursos</strong>
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
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Tipo do Curso</th>
                    <th>Início</th>
                    <th>Fim</th>
                    <th colspan="2">Ações</th>
                </thead>
                <tbody>
                    @foreach ($courses as $course)
                        <tr>
                            <td>{{ $course->name }}</td>
                            <td>{{ $course->description }}</td>
                            <td>{{ $course->courseType->name }}</td>
                            <td>{{ $course->begin }}</td>
                            <td>{{ $course->end }}</td>
                            <td><a href="{{ route('courses.edit', $course) }}">Editar</a></td>
                            <td>
                                <form name="{{ 'formDelete' . $course->id }}" action="{{ route('courses.destroy', $course) }}"
                                    method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <span
                                        onclick="{{ 'if(confirm(\'Tem certeza que deseja excluir esse curso?\')) document.forms[\'formDelete' . $course->id . '\'].submit();' }}"
                                        style="cursor:pointer; color:blue; text-decoration:underline;">Excluir</span>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {!! $courses->links() !!}
            <br />
        </main>
    </section>
@endsection
