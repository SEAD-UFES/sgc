@extends('layouts.basic')

@section('title', 'Exibir Colaborador')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Colaboradores</li>
            <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Listar Colaboradores</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $employee->name }}</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-xxl-8">
                    
                    @include('_components.alerts')

                    <h4>Colaborador: {{ $employee->name }}</h4>

                    <div class="card mb-3">
                        <div class="card-header" data-bs-toggle="collapse" href="#employeePersonalDataContent" role="button" aria-expanded="true" aria-controls="employeePersonalDataContent">
                            <h4 class='mb-0'>Dados Pessoais</h4>
                        </div>
                        <div class="collapse show" id="employeePersonalDataContent" >
                            <div class="card-body">
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Nome:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ $employee->name ?? '-' }}</div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>CPF:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ isset($employee->cpf) ? preg_replace('~(\d{3})(\d{3})(\d{3})(\d{2})~', '$1.$2.$3-$4', $employee->cpf) : '-' }}
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Profissão:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ $employee->personalDetail->job ?? '-' }}
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Gênero:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ $employee->gender->label() ?? '-' }}
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Data de Nascimento:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ $employee->personalDetail->birth_date != null ? \Carbon\Carbon::parse($employee->birth_date)->isoFormat('DD/MM/Y') : '-' }}
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>UF Nascimento:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ $employee->personalDetail->birth_state->name ?? '-' }}
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Cidade de Nascimento:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ $employee->personalDetail->birth_city ?? '-' }}
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Tipo de Documento:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ $employee->identity->type->name ?? '-' }}
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Número do Documento:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ $employee->identity->number ?? '-' }}
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Data de Expedição:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ $employee->identity->issue_date != null ? \Carbon\Carbon::parse($employee->id_issue_date)->isoFormat('DD/MM/Y') : '-' }}
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Orgão Expedidor:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ $employee->identity->issuer ?? '-' }}
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Estado Expedidor:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ $employee->identity->issuer_state ?? '-' }}
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Estado Civil:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ $employee->personalDetail->marital_status->label() ?? '-' }}
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Nome cônjuge:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ $employee->spouse->name ?? '-' }}
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Nome do pai:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ $employee->personalDetail->father_name ?? '-' }}
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Nome da mãe:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ $employee->personalDetail->mother_name ?? '-' }}
                                    </div>
                                </div>
                        
                                <div class="">
                                    @can('employee-update', $employee)
                                        <a href="{{ route('employees.edit', $employee->id) }}" data-bs-toggle="tooltip" title="Editar colaborador" class="btn btn-primary btn-sm">
                                            <i class="bi-pencil-fill"></i> Editar dados pessoais
                                        </a>&nbsp;
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header collapsed" data-bs-toggle="collapse" href="#employeeContactDataContent" role="button" aria-expanded="false" aria-controls="employeeContactDataContent">
                            <h4 class='mb-0'>Contato e Endereço</h4>
                        </div>
                        <div class="collapse" id="employeeContactDataContent" >
                            <div class="card-body">
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Código de Área:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ $employee->phones->where('type', 'Celular')->first()->area_code ?? '-' }}
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Telefone:</strong></div>
                                    @php
                                        $landline = $employee->phones->where('type', 'Fixo')->first();
                                    @endphp
                                    @if ($landline != null)
                                        <div class="col-sm-8 col-lg-9"><a href='tel:{{ $landline->area_code . $landline->number }}'>{{ preg_replace('~(\d{2})[^\d]{0,7}(\d{4})[^\d]{0,7}(\d{4})~', '($1) $2-$3', $landline->area_code . $landline->number) }}</a>
                                        </div>
                                    @else
                                        <div class="col-sm-8 col-lg-9">-
                                        </div>
                                    @endif
                                </div>
                                <div class="mb-2 row">
                                    @php
                                        $mobile = $employee->phones->where('type', 'Celular')->first();
                                    @endphp
                                    <div class="col-sm-4 col-lg-3"><strong>Celular:</strong></div>
                                    <div class="col-sm-8 col-lg-9"><a href='tel:{{ $mobile->area_code . $mobile->number }}'>{{ preg_replace('~(\d{2})[^\d]{0,7}(\d{5})[^\d]{0,7}(\d{4})~', '($1) $2-$3', $mobile->area_code . $mobile->number) }}</a>
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Email:</strong></div>
                                    <div class="col-sm-8 col-lg-9"><a href='mailto:{{ $employee->email }}'>{{ $employee->email ?? '-' }}</a>
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Logradouro:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ $employee->address->street ?? '-' }}
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Complemento:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ $employee->address->complement ?? '-' }}
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Número:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ $employee->address->number ?? '-' }}
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Bairro:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ $employee->address->district ?? '-' }}
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>CEP:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ isset($employee->address->zip_code) ? preg_replace('~(\d{2})(\d{3})(\d{3})~', '$1$2-$3', $employee->address->zip_code) : '-' }}
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>UF:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ $employee->address->state->name ?? '-' }}
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Cidade:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ $employee->address->city ?? '-' }}
                                    </div>
                                </div>

                                <div class="">
                                    @can('employee-update', $employee)
                                        <a href="{{ route('employees.edit', $employee->id) }}" data-bs-toggle="tooltip" title="Editar colaborador" class="btn btn-primary btn-sm">
                                            <i class="bi-pencil-fill"></i> Editar contato e endereço
                                        </a>&nbsp;
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header collapsed" data-bs-toggle="collapse" href="#employeeBankAccountDataContent" role="button" aria-expanded="false" aria-controls="employeeBankAccountDataContent">
                            <h4 class='mb-0'>Informações Bancárias</h4>
                        </div>
                        <div class="collapse" id="employeeBankAccountDataContent" >
                            <div class="card-body">
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Banco:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ $employee->bankAccount->bank_name ?? '-' }}
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Agência:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ $employee->bankAccount->agency_number ?? '-' }}
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Conta Corrente:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ $employee->bankAccount->account ?? '-' }}
                                    </div>
                                </div>

                                <div class="">
                                    @can('employee-update', $employee)
                                        <a href="{{ route('employees.edit', $employee->id) }}" data-bs-toggle="tooltip" title="Editar colaborador" class="btn btn-primary btn-sm">
                                            <i class="bi-pencil-fill"></i> Editar Informações Bancárias
                                        </a>&nbsp;
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header collapsed" data-bs-toggle="collapse" href="#bondListContent" role="button" aria-expanded="false" aria-controls="bondListContent">
                            <h4 class='mb-0'>Vínculos Ativos</h4>
                        </div>
                        <div class="collapse" id="bondListContent" >
                            <div class="card-body">
                                @if($employee->activeBonds->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <th>Início</th>
                                                <th>Fim</th>
                                                <th>Função</th>
                                                <th>Curso</th>
                                                <th>Polo</th>
                                                <th>Voluntário</th>
                                                <th>Impedido</th>
                                                <th class="text-center">Ações</th>
                                            </thead>
                                            <tbody>
                                                @foreach ($employee->activeBonds as $bond)
                                                    <tr>
                                                        <td>
                                                            {{ \Carbon\Carbon::parse($bond->begin)->isoFormat('DD/MM/Y') }}
                                                        </td>
                                                        <td>
                                                            {{$bond->end 
                                                                ? \Carbon\Carbon::parse($bond->end)->isoFormat('DD/MM/Y') 
                                                                : '-'
                                                            }}
                                                        </td>
                                                        <td>
                                                            {{ $bond->role->name }}
                                                        </td>
                                                        <td>
                                                            {{ $bond->course? $bond->course->name : '-' }}
                                                        </td>
                                                        <td>
                                                            {{ $bond->pole? $bond->pole->name: '-' }}
                                                        </td>
                                                        <td>
                                                            {{ $bond->volunteer === 1 ? 'Sim' : 'Não' }}
                                                        </td>
                                                        <td>
                                                            {{ $bond->impediment === 1 ? 'Sim' : 'Não' }}
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="d-inline-flex">
                                                                @can('bond-show')
                                                                    <a href="{{ route('bonds.show', $bond) }}" data-bs-toggle="tooltip" title="Ver Vínculo" class="btn btn-primary btn-sm">
                                                                        <i class="bi-eye-fill"></i>
                                                                    </a>&nbsp; 
                                                                @endcan
                                                                @can('bond-update', $bond)
                                                                    <a href="{{ route('bonds.edit', $bond->id) }}" data-bs-toggle="tooltip" title="Editar vínculo" class="btn btn-primary btn-sm">
                                                                        <i class="bi-pencil-fill"></i>
                                                                    </a>&nbsp;
                                                                @endcan
                                                                @can('bond-destroy')
                                                                    <form name="{{ 'formDelete' . $bond->id }}"
                                                                        action="{{ route('bonds.destroy', $bond) }}" method="POST">
                                                                        @method('DELETE')
                                                                        @csrf
                                                                        <button type="button" data-bs-toggle="tooltip" title="Excluir" 
                                                                            onclick="{{ 'if(confirm(\'Tem certeza que deseja excluir esse Vínculo?\')) document.forms[\'formDelete' . $bond->id . '\'].submit();' }}" class="btn btn-danger btn-sm">
                                                                            <i class="bi-trash-fill"></i>
                                                                        </button>
                                                                    </form>
                                                                @endcan
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="mb-0">O colaborador não possui vínculos ativos cadastrados.</p>
                                @endif
                                <div class="">
                                    <a class="btn btn-primary btn-sm" href="{{ route('bonds.index', ['employeeCpfContains[0]' => $employee->cpf]) }}" target="_blank">
                                        Listagem avançada (com todos os vínculos)...
                                    </a>    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header collapsed" data-bs-toggle="collapse" href="#institutionDetails" role="button"
                            aria-expanded="false" aria-controls="institutionDetails">
                            <h4 class='mb-0'>Detalhes Institucionais</h4>
                        </div>
                        <div class="collapse" id="institutionDetails">
                            <div class="card-body">
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Login Institucional:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ $employee->institutionalDetail->login ?? '-' }}</div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-sm-4 col-lg-3"><strong>Email Institucional:</strong></div>
                                    <div class="col-sm-8 col-lg-9">{{ $employee->institutionalDetail->email ?? '-' }}</div>
                                </div>
                                <div class="">
                                    @can('employee-update', $employee)
                                        <a href="{{ route('employees.institutional_details.edit', $employee->id) }}" data-bs-toggle="tooltip" title="Editar Detalhes Institucionais" class="btn btn-primary btn-sm">
                                            <i class="bi-pencil-fill"></i> Editar Detalhes Institucionais
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header collapsed" data-bs-toggle="collapse" href="#activityDetails" role="button"
                            aria-expanded="false" aria-controls="activityDetails">
                            <h4 class='mb-0'>Informações de Cadastro</h4>
                        </div>
                        <div class="collapse" id="activityDetails">
                            <div class="card-body">
                                <div class="mb-2 row">
                                    <div class="col-sm-12 col-lg-12"><h5>Cadastrado</h5></div>
                                    <div class="col-sm-6 col-lg-6"><strong>Por:</strong> {{ $employee->createdBy?->login ?? '-' }}</div>
                                    <div class="col-sm-6 col-lg-6"><strong>Em:</strong> {{ (is_null($employee->createdOn)) ? '-' : \Carbon\Carbon::parse($employee->createdOn)->isoFormat('DD/MM/Y HH:mm') }}</div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-sm-12 col-lg-12"><h5>Atualizado por último</h5></div>
                                    <div class="col-sm-6 col-lg-6"><strong>Por:</strong> {{ $employee->updatedBy?->login ?? '-' }}</div>
                                    <div class="col-sm-6 col-lg-6"><strong>Em:</strong> {{ (is_null($employee->updatedOn)) ? '-' : \Carbon\Carbon::parse($employee->updatedOn)->isoFormat('DD/MM/Y HH:mm') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header collapsed" data-bs-toggle="collapse" href="#userListContent" role="button" aria-expanded="false" aria-controls="userListContent">
                            <h4 class='mb-0'>Usuários de sistema associados</h4>
                        </div>
                        <div class="collapse" id="userListContent" >
                            <div class="card-body">
                                @if($employee->user != null)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <th>Usuário</th>
                                                <th>Ativo</th>
                                                <th>Criado em</th>
                                                <th>Última atualização</th>
                                                <th class="text-center" >Ações</th>
                                            </thead>
                                            <tbody>
                                                {{-- @foreach ($employee->users as $user) --}}
                                                    <tr>
                                                        <td>
                                                            {{ $employee->user->login }}
                                                        </td>
                                                        <td>
                                                            {{ $employee->user->active === 1 ? 'Sim' : 'Não' }}
                                                        </td>
                                                        <td>
                                                            {{ (isset($bond) && $employee->user->created_at )
                                                                ? \Carbon\Carbon::parse($bond->end)->isoFormat('DD/MM/Y') 
                                                                : '-'
                                                            }}
                                                        </td>
                                                        <td>
                                                            {{ (isset($bond) && $employee->user->updated_at) 
                                                                ? \Carbon\Carbon::parse($bond->end)->isoFormat('DD/MM/Y') 
                                                                : '-'
                                                            }}
                                                        </td>
                                                        <td class="text-center"><div class="d-inline-flex">
                                                            @can('user-show')
                                                                <a href="{{route('users.show', $employee->user)}}" data-bs-toggle="tooltip" title="Ver usuário" class="btn btn-primary btn-sm"><i class="bi-eye-fill"></i></a>&nbsp;
                                                            @endcan
                                                            @can('user-update')
                                                                <a href="{{route('users.edit', $employee->user)}}" data-bs-toggle="tooltip" title="Editar usuário" class="btn btn-primary btn-sm"><i class="bi-pencil-fill"></i></a>&nbsp;
                                                            @endcan
                                                            @can('user-destroy')
                                                                <form name="{{ 'formDelete' . $employee->user->id }}" action="{{ route('users.destroy', $employee->user) }}" method="POST">
                                                                    @method('DELETE')
                                                                    @csrf
                                                                    <button type="button" data-bs-toggle="tooltip" title="Excluir usuário" onclick="{{ 'if(confirm(\'Tem certeza que deseja excluir esse usuário?\')) document.forms[\'formDelete' . $employee->user->id . '\'].submit();' }}" class="btn btn-danger btn-sm">
                                                                        <i class="bi-trash-fill"></i>
                                                                    </button>
                                                                </form>
                                                            @endcan
                                                        </td>
                                                    </tr>
                                                {{-- @endforeach --}}
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="mb-0">O colaborador não possui usuários de sistema associados.</p>
                                @endif

                                <div class="">
                                    <a class="btn btn-primary btn-sm mt-1" href="{{ route('users.index', ['employee_id' => $employee->id]) }}" target="_blank">
                                        Listagem avançada...
                                    </a>    
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary">Lista de Colaboradores</a>
                    <br/><br/>
                </div>
            </div>
        </main>
    </section>
@endsection
