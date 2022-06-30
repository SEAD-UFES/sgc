@extends('layouts.basic')

@section('title', 'Colaboradores')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Colaboradores</li>
            <li class="breadcrumb-item active" aria-current="page">Listar Colaboradores</li>
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
                            'filters' =>$filters,
                            'options' => [
                                [ 'label'=>'CPF', 'value'=>'cpfContains', 'selected'=>true],
                                [ 'label'=>'Nome', 'value'=>'nameContains'],
                                [ 'label'=>'Profissão', 'value'=>'jobContains'],
                                [ 'label'=>'Cidade', 'value'=>'addresscityContains'],
                                [ 'label'=>'Usuário', 'value'=>'userEmailContains'],
                            ]
                        ]
                    )@endcomponent
                    
                    <p style="color: red"> Clique no CPF ou Nome para exibir/ocultar as informações de contato</p>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <th>@sortablelink('cpf', 'CPF')</th>
                                <th>@sortablelink('name', 'Nome')</th>
                                <th>@sortablelink('job', 'Profissão')</th>
                                <th>@sortablelink('address_city', 'Cidade')</th>
                                @can('isAdm-global')
                                    <th>@sortablelink('user.email', 'Usuário')</th>
                                @endcan
                                <th class="text-center">Ações</th>
                            </thead>
                            <tbody>
                                @foreach ($employees as $employee)
                                    <tr>
                                        <td data-bs-html="true" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="bottom" 
                                        data-bs-content="<strong>E-mail:</strong> <a href='mailto:{{ $employee->email }}'>{{ $employee->email }}</a> | <strong>Área:</strong> {{ $employee->area_code }} | <strong>Telefone:</strong> <a href='tel:{{ $employee->phone }}'>{{ preg_replace('~(\d{2})[^\d]{0,7}(\d{4})[^\d]{0,7}(\d{4})~', '($1) $2-$3', $employee->phone) }}</a> | <strong>Celular:</strong> <a href='tel:{{ $employee->mobile }}'>{{ preg_replace('~(\d{2})[^\d]{0,7}(\d{5})[^\d]{0,7}(\d{4})~', '($1) $2-$3', $employee->mobile) }}</a>">
                                            {{ preg_replace('~(\d{3})(\d{3})(\d{3})(\d{2})~', '$1.$2.$3-$4', $employee->cpf) }}
                                        </td>
                                        <td data-bs-html="true" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="bottom" 
                                        data-bs-content="<strong>E-mail:</strong> <a href='mailto:{{ $employee->email }}'>{{ $employee->email }}</a> | <strong>Área:</strong> {{ $employee->area_code }} | <strong>Telefone:</strong> <a href='tel:{{ $employee->phone }}'>{{ preg_replace('~(\d{2})[^\d]{0,7}(\d{4})[^\d]{0,7}(\d{4})~', '($1) $2-$3', $employee->phone) }}</a> | <strong>Celular:</strong> <a href='tel:{{ $employee->mobile }}'>{{ preg_replace('~(\d{2})[^\d]{0,7}(\d{5})[^\d]{0,7}(\d{4})~', '($1) $2-$3', $employee->mobile) }}</a>">
                                            {{ $employee->name }}
                                        </td>
                                        <td>{{ $employee->job }}</td>
                                        <td>{{ $employee->address_city }}</td>
                                        @can('isAdm-global')
                                            <td>{{ $employee->user->email ?? '' }}</td>
                                        @endcan
                                        <td class="text-center"><div class="d-inline-flex">
                                            @can('employee-show')
                                                <a href="{{ route('employees.show', $employee) }}" data-bs-toggle="tooltip" title="Ver colaborador" class="btn btn-primary btn-sm">
                                                    <i class="bi-eye-fill"></i>
                                                </a>&nbsp;
                                            @endcan
                                            @can('employee-update')
                                                <a href="{{ route('employees.edit', $employee) }}" data-bs-toggle="tooltip" title="Editar colaborador" class="btn btn-primary btn-sm">
                                                    <i class="bi-pencil-fill"></i>
                                                </a>&nbsp;
                                            @endcan
                                            @can('employee-destroy')
                                                <form name="{{ 'formDelete' . $employee->id }}"
                                                    action="{{ route('employees.destroy', $employee) }}" method="POST">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="button" data-bs-toggle="tooltip" title="Excluir colaborador" 
                                                        onclick="{{ 'if(confirm(\'Tem certeza que deseja excluir esse Colaborador e todos os seus documentos, vínculos e documentos de vínculos?\')) document.forms[\'formDelete' . $employee->id . '\'].submit();' }}" 
                                                        class="btn btn-danger btn-sm">
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
                    {!! $employees->links() !!}
                    <a href="{{ route('home') }}" class="btn btn-secondary">Voltar para o Início</a>
                    @can('employee-store')
                        <a href="{{ route('employees.create') }}" class="btn btn-warning">Cadastrar novo Colaborador</a>
                    @endcan
                    <br /><br />
                </div>
            </div>
        </main>
    </section>
@endsection

@section('scripts')
    @component('_components.filters_script', ['filters' =>$filters] )@endcomponent
    <script src="{{ asset('js/enable_tooltip_popover.js') }}"></script>
@endsection