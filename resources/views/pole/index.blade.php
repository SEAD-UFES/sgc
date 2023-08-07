@extends('layouts.basic')

@section('title', 'Polos')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Sistema</li>
            <li class="breadcrumb-item active" aria-current="page">Listar Polos</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-xxl-8">
                    @include('_components.alerts')

                    {{-- filtros --}}
                    @component(
                        '_components.filters_form', 
                        [
                            'filters' => $filters,
                            'options' => [
                                ['label' => 'Nome', 'value' => 'nameContains', 'selected' => true], 
                                ['label' => 'Descrição', 'value' => 'descriptionContains']
                            ], 
                        ]
                    )@endcomponent
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <th>@sortablelink('name', 'Nome')</th>
                                <th>@sortablelink('description', 'Descrição')</th>
                                <th class="text-center">Ações</th>
                            </thead>
                            <tbody>
                                @foreach ($poles as $pole)
                                    <tr>
                                        <td>{{ $pole->name }}</td>
                                        <td>{{ $pole->description }}</td>
                                        <td class="text-center"><div class="d-inline-flex">
                                            @can('pole-update')
                                                <a href="{{ route('poles.edit', $pole) }}" data-bs-toggle="tooltip" title="Editar polo" class="btn btn-primary btn-sm">
                                                    <i class="bi-pencil-fill"></i>
                                                </a>&nbsp;
                                            @endcan
                                            @can('pole-destroy')
                                                <form name="{{ 'formDelete' . $pole->id }}" action="{{ route('poles.destroy', $pole) }}"
                                                    method="POST">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="button" data-bs-toggle="tooltip" title="Excluir polo" 
                                                        onclick="{{ 'if(confirm(\'Tem certeza que deseja excluir esse Polo?\')) document.forms[\'formDelete' . $pole->id . '\'].submit();' }}" class="btn btn-danger btn-sm">
                                                        <i class="bi-trash-fill"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <br />
                    {!! $poles->links() !!}
                    <a href="{{ route('home') }}" class="btn btn-secondary">Voltar para o Início</a>
                    @can('pole-store')
                        <a href="{{ route('poles.create') }}" class="btn btn-warning">Cadastrar novo Polo</a>
                    @endcan
                    <br /><br />
                </div>
            </div>
        </main>
    </section>
@endsection

@section('scripts')
    @component('_components.filters_script', ['filters' => $filters])@endcomponent
@endsection
