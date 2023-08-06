@extends('layouts.basic')

@section('title', 'Usuários')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Sistema</li>
            <li class="breadcrumb-item active" aria-current="page">Listar Usuários</li>
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
                                ['label' => 'Login', 'value' => 'loginContains', 'selected' => true], 
                                // ['label' => 'Tipo', 'value' => 'usertypeNameContains'], 
                                ['label' => 'Ativo', 'value' => 'activeExactly'], 
                                ['label' => 'Colaborador', 'value' => 'employeeNameContains']
                            ],
                        ]
                    )@endcomponent
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <th>@sortablelink('login', 'Login')</th>
                                <th>@sortablelink('active', 'Ativo')</th>
                                <th>@sortablelink('employee.name', 'Colaborador')</th>
                                <th class="text-center">Ações</th>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->login }}</td>
                                        <td>{{ $user->active === 1 ? 'Sim' : 'Não' }}</td>
                                        <td>{{ $user->employee ? $user->employee->name : 'Não possui' }}</td>
                                        <td class="text-center"><div class="d-inline-flex">
                                            @can('user-show')
                                                <a href="{{route('users.show', $user)}}" data-bs-toggle="tooltip" title="Ver usuário" class="btn btn-primary btn-sm"><i class="bi-eye-fill"></i></a>&nbsp;
                                            @endcan
                                            @can('user-update')
                                                <a href="{{route('users.edit', $user)}}" data-bs-toggle="tooltip" title="Editar usuário" class="btn btn-primary btn-sm"><i class="bi-pencil-fill"></i></a>&nbsp;
                                            @endcan
                                            @can('user-destroy')
                                                <form name="{{ 'formDelete' . $user->id }}" action="{{ route('users.destroy', $user) }}" method="POST">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="button" data-bs-toggle="tooltip" title="Excluir usuário" onclick="{{ 'if(confirm(\'Tem certeza que deseja excluir esse usuário?\')) document.forms[\'formDelete' . $user->id . '\'].submit();' }}" class="btn btn-danger btn-sm">
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
                    {!! $users->links() !!}
                    <a href="{{ route('home') }}" class="btn btn-secondary">Voltar para o Início</a>
                    @can('isAdm-global')
                        <a href="{{ route('users.create') }}" class="btn btn-warning">Cadastrar novo Usuário</a>
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
