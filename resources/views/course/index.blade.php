@extends('layouts.basic')

@section('title', 'Cursos')

@section('content')
<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
    <ol class="breadcrumb border-top border-bottom bg-light">
        <li class="breadcrumb-item">{{-- <a href="{{ route('system') }}"> --}}Sistema{{-- </a> --}}</li>
        <li class="breadcrumb-item active" aria-current="page">Listar Cursos</li>
    </ol>
</nav>
    <section id="pageContent">
        <main role="main">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <span style="color: green; font-weight: bold">{{ $message }}</span>
                </div>
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

            <table class="table table-striped table-hover">
                <thead>
                    <th>@sortablelink('name', 'Nome')</th>
                    <th>@sortablelink('description', 'Descrição')</th>
                    <th>@sortablelink('courseType.name', 'Tipo do Curso')</th>
                    <th>@sortablelink('begin', 'Início')</th>
                    <th>@sortablelink('end', 'Fim')</th>
                    <th colspan="2" class="text-center">Ações</th>
                </thead>
                <tbody>
                    @foreach ($courses as $course)
                        <tr>
                            <td>{{ $course->name }}</td>
                            <td>{{ $course->description }}</td>
                            <td>{{ $course->courseType->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($course->begin)->isoFormat('DD/MM/Y') }}</td> 
                            <td>{{ \Carbon\Carbon::parse($course->end)->isoFormat('DD/MM/Y') }}</td>
                            <td class="text-center"><a href="{{ route('courses.edit', $course) }}" class="btn btn-primary btn-sm">Editar</a></td>
                            <td class="text-center">
                                <form name="{{ 'formDelete' . $course->id }}" action="{{ route('courses.destroy', $course) }}"
                                    method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <span
                                        onclick="{{ 'if(confirm(\'Tem certeza que deseja excluir esse curso?\')) document.forms[\'formDelete' . $course->id . '\'].submit();' }}"
                                        {{-- style="cursor:pointer; color:blue; text-decoration:underline;" --}} class="btn btn-danger btn-sm">Excluir</span>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {!! $courses->links() !!}
            <button type="button" onclick="history.back()" class="btn btn-secondary">Voltar</button>
            <br /><br />
        </main>
    </section>
@endsection

@section('scripts')

@component('_components.filters_script', ['filters' =>$filters] )@endcomponent

@endsection