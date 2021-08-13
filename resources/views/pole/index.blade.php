@extends('layouts.basic')

@section('title', 'Polos')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">{{-- <a href="{{ route('system') }}"> --}}Sistema{{-- </a> --}}</li>
            <li class="breadcrumb-item active" aria-current="page">Listar Polos</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <span style="color: green; font-weight: bold">{{ $message }}</span>
                </div>
            @endif

            {{-- filtros --}}
            @component(
                '_components.filters_form', 
                [
                    'filters' => $filters,
                    'options' => [
                        ['label' => 'Nome', 'value' => 'name_contains', 'selected' => true], 
                        ['label' => 'Descrição', 'value' => 'description_contains']
                    ], 
                ]
            )@endcomponent

            <table class="table table-striped table-hover">
                <thead>
                    <th>@sortablelink('name', 'Nome')</th>
                    <th>@sortablelink('description', 'Descrição')</th>
                    <th colspan="2" class="text-center">Ações</th>
                </thead>
                <tbody>
                    @foreach ($poles as $pole)
                        <tr>
                            <td>{{ $pole->name }}</td>
                            <td>{{ $pole->description }}</td>
                            <td class="text-center"><a href="{{ route('poles.edit', $pole) }}" class="btn btn-primary btn-sm">Editar</a></td>
                            <td class="text-center">
                                <form name="{{ 'formDelete' . $pole->id }}" action="{{ route('poles.destroy', $pole) }}"
                                    method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <span
                                        onclick="{{ 'if(confirm(\'Tem certeza que deseja excluir esse Polo?\')) document.forms[\'formDelete' . $pole->id . '\'].submit();' }}"
                                        {{-- style="cursor:pointer; color:blue; text-decoration:underline;" --}} class="btn btn-danger btn-sm">Excluir</span>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {!! $poles->links() !!}
            <button type="button" onclick="history.back()" class="btn btn-secondary">Voltar</button>
            <br /><br />
        </main>
    </section>
@endsection

@section('scripts')

    @component('_components.filters_script', ['filters' => $filters])@endcomponent

@endsection
