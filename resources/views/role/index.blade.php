@extends('layouts.basic')

@section('title', 'Funções')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Sistema</li>
            <li class="breadcrumb-item active" aria-current="page">Listar Funções</li>
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
                                ['label' => 'Nome', 'value' => 'name_contains', 'selected' => true],
                                ['label' => 'Descrição', 'value' => 'description_contains'], 
                                ['label' => 'Valor da Bolsa (=)', 'value' => 'grantvalue_exactly'], 
                                ['label' => 'Valor da Bolsa (>=)', 'value' => 'grantvalue_BigOrEqu'], 
                                ['label' => 'Valor da Bolsa (<=)', 'value'=> 'grantvalue_LowOrEqu'], 
                                ['label' => 'Tipo da bolsa', 'value' => 'grantType_name_contains']
                            ],
                        ]
                    )@endcomponent
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <th>@sortablelink('name', 'Nome')</th>
                                <th>@sortablelink('description', 'Descrição')</th>
                                <th>@sortablelink('grant_value', 'Valor da Bolsa')</th>
                                <th>@sortablelink('grantType.name', 'Tipo da Bolsa')</th>
                                <th class="text-center">Ações</th>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                    <tr>
                                        <td>{{ $role->name }}</td>
                                        <td>{{ $role->description }}</td>
                                        <td>{{ numfmt_format_currency(numfmt_create('pt_BR', NumberFormatter::CURRENCY), $role->grant_value, 'BRL') }}</td>
                                        <td>{{ $role->grantType->name }}</td>
                                        <td class="text-center"><div class="d-inline-flex">
                                            @can('role-update')
                                                <a href="{{ route('roles.edit', $role) }}" data-bs-toggle="tooltip" title="Editar Função" class="btn btn-primary btn-sm">
                                                    <i class="bi-pencil-fill"></i>
                                                </a>&nbsp;
                                            @endcan
                                            @can('role-destroy')
                                                <form name="{{ 'formDelete' . $role->id }}" action="{{ route('roles.destroy', $role) }}" method="POST">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="button" data-bs-toggle="tooltip" title="Excluir Função" 
                                                        onclick="{{ 'if(confirm(\'Tem certeza que deseja excluir essa Função?\')) document.forms[\'formDelete' . $role->id . '\'].submit();' }}" class="btn btn-danger btn-sm">
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
                    {!! $roles->links() !!}
                    <button type="button" onclick="history.back()" class="btn btn-secondary">Voltar</button>
                    @can('role-store')
                        <a href="{{ route('roles.create') }}" class="btn btn-warning">Cadastrar nova Função</a>
                    @endcan
                    <br /><br />
                </div>
            </div>
        </main>
    </section>
@endsection

@section('scripts')
    @component('_components.filters_script', ['filters' => $filters])@endcomponent
    <script src="{{ asset('js/enable_tooltip_popover.js') }}"></script>
@endsection
