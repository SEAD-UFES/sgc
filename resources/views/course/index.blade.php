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

            {{-- local para os filtros --}}
            @component(
                '_components.filters_form', 
                [
                    'filters' =>$filters,
                    'options' => [
                        [ 'label'=>'Nome', 'value'=>'name_contains', 'selected'=>true],
                        [ 'label'=>'Descrição', 'value'=>'description_contains'],
                        [ 'label'=>'Tipo', 'value'=>'courseType_name_contains'],
                        [ 'label'=>'Início (=)', 'value'=>'begin_exactly'],
                        [ 'label'=>'Início (>=)' , 'value'=>'begin_BigOrEqu'],
                        [ 'label'=>'Início (<=)', 'value'=>'begin_LowOrEqu'],
                        [ 'label'=>'Fim (=)', 'value'=>'end_exactly'],
                        [ 'label'=>'Fim (>=)' , 'value'=>'end_BigOrEqu'],
                        [ 'label'=>'Fim (<=)', 'value'=>'end_LowOrEqu'],
                    ]
                ]
            )@endcomponent

            <br/>

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

@section('scripts')

@component('_components.filters_script', ['filters' =>$filters] )@endcomponent

@endsection