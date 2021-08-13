@extends('layouts.basic')

@section('title', 'Atribuições')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">{{-- <a href="{{ route('system') }}"> --}}Sistema{{-- </a> --}}</li>
            <li class="breadcrumb-item active" aria-current="page">Listar Atribuições</li>
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
                        ['label' => 'Descrição', 'value' => 'description_contains'], 
                        ['label' => 'Valor da Bolsa (=)', 'value' => 'grantvalue_exactly'], 
                        ['label' => 'Valor da Bolsa (>=)', 'value' => 'grantvalue_BigOrEqu'], 
                        ['label' => 'Valor da Bolsa (<=)', 'value'=> 'grantvalue_LowOrEqu'], 
                        ['label' => 'Tipo da bolsa', 'value' => 'grantType_name_contains']
                    ],
                ]
            )@endcomponent

            <table class="table table-striped table-hover">
                <thead>
                    <th>@sortablelink('name', 'Nome')</th>
                    <th>@sortablelink('description', 'Descrição')</th>
                    <th>@sortablelink('grant_value', 'Valor da Bolsa')</th>
                    <th>@sortablelink('grantType.name', 'Tipo da Bolsa')</th>
                    <th colspan="2" class="text-center">Ações</th>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td>{{ $role->name }}</td>
                            <td>{{ $role->description }}</td>
                            <td>{{ numfmt_format_currency(numfmt_create('pt_BR', NumberFormatter::CURRENCY), $role->grant_value, 'BRL') }}</td>
                            <td>{{ $role->grantType->name }}</td>
                            <td class="text-center"><a href="{{ route('roles.edit', $role) }}" data-bs-toggle="tooltip" title="Editar" class="btn btn-primary btn-sm">
                                <i class="bi-pencil-fill"></i>
                            </a></td>
                            <td class="text-center">
                                <form name="{{ 'formDelete' . $role->id }}" action="{{ route('roles.destroy', $role) }}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button type="button" data-bs-toggle="tooltip" title="Excluir" 
                                        onclick="{{ 'if(confirm(\'Tem certeza que deseja excluir essa Atribuição?\')) document.forms[\'formDelete' . $role->id . '\'].submit();' }}"
                                        {{-- style="cursor:pointer; color:blue; text-decoration:underline;" --}} class="btn btn-danger btn-sm">
                                        <i class="bi-trash-fill"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {!! $roles->links() !!}
            <button type="button" onclick="history.back()" class="btn btn-secondary">Voltar</button>
            <br /><br />
        </main>
    </section>
@endsection

@section('scripts')
    @component('_components.filters_script', ['filters' => $filters])@endcomponent
@endsection
