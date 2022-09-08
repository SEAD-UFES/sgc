@extends('layouts.basic')

@section('title', 'Exibir Usuário')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Usuários</li>
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Listar Usuários</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $user->email }}</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-xxl-8">

                    @include('_components.alerts')

                    <div class="card mb-3">
                        <div class="card-header" data-bs-toggle="collapse" href="#userDataContent" role="button"
                            aria-expanded="true" aria-controls="userDataContent">
                            <h4 class='mb-0'>Dados do usuário</h4>
                        </div>
                        <div class="collapse show" id="userDataContent">
                            <div class="card-body">
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Login:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ $user->email ?? '-' }}</div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Status:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ $user->active ? 'Ativo' : 'Inativo' }}</div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Criado em:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ $user->created_at ?? '-' }}</div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Última atualização:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ $user->updated_at ?? '-' }}</div>
                                </div>

                                <div class="">
                                    @can('user-update', $user)
                                        <a href="{{ route('users.edit', $user->id) }}" data-bs-toggle="tooltip"
                                            title="Editar usuário" class="btn btn-primary btn-sm">
                                            <i class="bi-pencil-fill"></i> Editar usuário
                                        </a>&nbsp;
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header" data-bs-toggle="collapse" href="#employeeDataContent" role="button"
                            aria-expanded="true" aria-controls="employeeDataContent">
                            <h4 class='mb-0'>Colaborador associado</h4>
                        </div>

                        <div class="collapse show" id="employeeDataContent">
                            <div class="card-body">
                                @if ($user->employee)
                                    <div class="mb-2 row">
                                        <div class="col-sm-4 col-lg-3"><strong>Nome:</strong></div>
                                        <div class="col-sm-8 col-lg-9">{{ $user->employee->name ?? '-' }}</div>
                                    </div>
                                    <div class="mb-2 row">
                                        <div class="col-sm-4 col-lg-3"><strong>CPF:</strong></div>
                                        <div class="col-sm-8 col-lg-9">
                                            {{ isset($user->employee->cpf) ? preg_replace('~(\d{3})(\d{3})(\d{3})(\d{2})~', '$1.$2.$3-$4', $user->employee->cpf) : '-' }}
                                        </div>
                                    </div>
                                    <div class="d-inline-flex">
                                        @can('employee-show')
                                            <a href="{{ route('employees.show', $user->employee->id) }}"
                                                class="btn btn-primary btn-sm">
                                                <i class="bi-eye-fill"></i> Consultar colaborador
                                            </a>&nbsp;
                                        @endcan
                                        @can('user-update')
                                            <a href="{{ route('users.edit', $user) }}" data-bs-toggle="tooltip"
                                                title="Editar associação" class="btn btn-primary btn-sm">
                                                <i class="bi-pencil-fill"></i> Editar Associação
                                            </a>&nbsp;
                                        @endcan
                                        @can('user-update')
                                            <form name="{{ 'formUnlink' . $user->id }}"
                                                action="{{ route('users.destroyEmployeeLink', $user) }}" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <button type="button" data-bs-toggle="tooltip" title="Desassociar colaborador"
                                                    onclick="{{ 'if(confirm(\'Tem certeza que deseja desassociar esse colaborador desse usuário?\')) document.forms[\'formUnlink' . $user->id . '\'].submit();' }}"
                                                    class="btn btn-danger btn-sm">
                                                    <i class="bi-trash-fill"></i> Desassociar colaborador
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                @else
                                    <p class="mb-0">O usuário não possui colaborador associado.</p>
                                    @can('user-update')
                                        <br />
                                        <a href="{{ route('users.edit', $user->id) }}" data-bs-toggle="tooltip"
                                            title="Editar associação" class="btn btn-primary btn-sm">
                                            <i class="bi-pencil-fill"></i> Editar Associação
                                        </a>&nbsp;
                                    @endcan
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header" data-bs-toggle="collapse" href="#utaListContent" role="button"
                            aria-expanded="true" aria-controls="utaListContent">
                            <h4 class='mb-0'>Papéis associados</h4>
                        </div>
                        <div class="collapse show" id="utaListContent">
                            <div class="card-body">
                                @php
                                    $userTypeAssignments = $user
                                        ->userTypeAssignments()
                                        ->orderBy('begin', 'ASC')
                                        ->get();
                                @endphp
                                @if ($userTypeAssignments->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <th>início</th>
                                                <th>Fim</th>
                                                <th>Papel</th>
                                                <th class="text-center">Ações</th>
                                            </thead>
                                            <tbody>
                                                @foreach ($userTypeAssignments as $uta)
                                                    <tr>
                                                        <td>
                                                            {{ $uta->begin ? \Carbon\Carbon::parse($uta->begin)->isoFormat('DD/MM/Y') : '-' }}
                                                        </td>
                                                        <td>
                                                            {{ $uta->end ? \Carbon\Carbon::parse($uta->end)->isoFormat('DD/MM/Y') : '-' }}
                                                        </td>
                                                        <td>
                                                            {{ $uta->userType->name }}
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="d-inline-flex">
                                                                @can('userTypeAssignment-update')
                                                                    <a href="{{ route('userTypeAssignments.edit', $uta) }}"
                                                                        data-bs-toggle="tooltip" title="Editar usuário"
                                                                        class="btn btn-primary btn-sm">
                                                                        <i class="bi-pencil-fill"></i>
                                                                    </a>&nbsp;
                                                                @endcan
                                                                @can('userTypeAssignment-destroy')
                                                                    <form name="{{ 'formDelete' . $uta->id }}"
                                                                        action="{{ route('userTypeAssignments.destroy', $uta) }}"
                                                                        method="POST">
                                                                        @method('DELETE')
                                                                        @csrf
                                                                        <button type="button" data-bs-toggle="tooltip"
                                                                            title="Excluir usuário"
                                                                            onclick="{{ 'if(confirm(\'Tem certeza que deseja excluir esse usuário?\')) document.forms[\'formDelete' . $uta->id . '\'].submit();' }}"
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
                                @else
                                    <p class="mb-0">O usuário não possui papéis associados.</p>
                                @endif

                                <div class="">
                                    <a class="btn btn-primary btn-sm mt-1"
                                        href="{{ route('userTypeAssignments.index', ['user_id' => $user->id]) }}"
                                        target="_blank">
                                        Listagem avançada...
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Lista de Usuários</a>
                    <br />
                </div>
            </div>
        </main>
    </section>
@endsection
