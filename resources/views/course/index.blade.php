@extends('layouts.basic')

@section('title', 'Cursos')

@section('content')
    <section>
        <h2>Cursos</h2>
    </section>
    <section id="pageContent">
        <main role="main">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p style="color: green; font-weight: bold">{{ $message }}</p>
                </div><br />
            @endif
            <table class="table table-striped table-hover">
                <thead>
                    <th>@sortablelink('name', 'Nome')</th>
                    <th>@sortablelink('description', 'Descrição')</th>
                    <th>@sortablelink('courseType.name', 'Tipo do Curso')</th>
                    <th>@sortablelink('begin', 'Início')</th>
                    <th>@sortablelink('end', 'Fim')</th>
                    <th colspan="2">Ações</th>
                </thead>
                <tbody>
                    @foreach ($courses as $course)
                        <tr>
                            <td>{{ $course->name }}</td>
                            <td>{{ $course->description }}</td>
                            <td>{{ $course->courseType->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($course->begin)->isoFormat('DD/MM/Y') }}</td> 
                            <td>{{ \Carbon\Carbon::parse($course->end)->isoFormat('DD/MM/Y') }}</td>
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
            <button type="button" onclick="history.back()" class="btn btn-secondary">Voltar</button>
        </main>
    </section>
@endsection
