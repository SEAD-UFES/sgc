@extends('layouts.basic')

@section('title', 'Atribuições')

@section('content')
    <section>
        <h2>Atribuições</h2>
    </section>
    <section id="pageContent">
        <main role="main">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p style="color: green; font-weight: bold">{{ $message }}</p>
                </div><br />
            @endif

            {{-- filtros --}}
            @component(
                '_components.filters_form', 
                [
                    'filters' =>$filters,
                    'options' => [
                        [ 'label'=>'Nome', 'value'=>'name_contains', 'selected'=>true],
                        [ 'label'=>'Descrição', 'value'=>'description_contains' ],
                        [ 'label'=>'Valor da Bolsa (=)', 'value'=>'grantvalue_exactly' ],
                        [ 'label'=>'Valor da Bolsa (>=)', 'value'=>'grantvalue_BigOrEqu' ],
                        [ 'label'=>'Valor da Bolsa (<=)', 'value'=>'grantvalue_LowOrEqu' ],
                        [ 'label'=>'Tipo da bolsa', 'value'=>'grantType_name_contains' ],
                    ]
                ]
            )@endcomponent

            <br/>

            <table class="table table-striped table-hover">
                <thead>
                    <th>@sortablelink('name', 'Nome')</th>
                    <th>@sortablelink('description', 'Descrição')</th>
                    <th>@sortablelink('grant_value', 'Valor da Bolsa')</th>
                    <th>@sortablelink('grantType.name', 'Tipo da Bolsa')</th>
                    <th colspan="2">Ações</th>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td>{{ $role->name }}</td>
                            <td>{{ $role->description }}</td>
                            <td>{{ numfmt_format_currency(numfmt_create('pt_BR', NumberFormatter::CURRENCY), $role->grant_value, 'BRL') }}</td>
                            <td>{{ $role->grantType->name }}</td>
                            <td><a href="{{ route('roles.edit', $role) }}">Editar</a></td>
                            <td>
                                <form name="{{ 'formDelete' . $role->id }}" action="{{ route('roles.destroy', $role) }}"
                                    method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <span
                                        onclick="{{ 'if(confirm(\'Tem certeza que deseja excluir essa Atribuição?\')) document.forms[\'formDelete' . $role->id . '\'].submit();' }}"
                                        style="cursor:pointer; color:blue; text-decoration:underline;">Excluir</span>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {!! $roles->links() !!}
            <br />
            <button type="button" onclick="history.back()" class="btn btn-secondary">Voltar</button>
        </main>
    </section>
@endsection

@section('scripts')

@component('_components.filters_script', ['filters' =>$filters] )@endcomponent

@endsection