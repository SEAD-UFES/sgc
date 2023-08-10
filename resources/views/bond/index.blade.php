@extends('layouts.basic')

@section('title', 'Vínculos')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Colaboradores</li>
            <li class="breadcrumb-item active" aria-current="page">Listar Vínculos</li>
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
                                [ 'label'=>'CPF', 'value'=>'employeeCpfContains', 'selected'=>true],
                                [ 'label'=>'Colaborador', 'value'=>'employeeNameContains'],
                                [ 'label'=>'Função', 'value'=>'roleNameContains'],
                                [ 'label'=>'Curso', 'value'=>'courseNameContains'],
                                [ 'label'=>'Polo', 'value'=>'poleNameContains'],
                                [ 'label'=>'Voluntário', 'value'=>'volunteerExactly'],
                                [ 'label'=>'Impedido', 'value'=>'impedimentExactly'],
                            ]
                        ]
                    )@endcomponent

                    <p style="color: red"> Clique no Nome ou Função para exibir/ocultar as informações de datas</p>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>@sortablelink('cpf', 'CPF')</th>
                                    <th>@sortablelink('name', 'Colaborador')</th>
                                    <th>@sortablelink('role', 'Função')</th>
                                    <th>@sortablelink('course', 'Curso')</th>
                                    <th>@sortablelink('pole', 'Polo')</th>
                                    <th>@sortablelink('volunteer', 'Voluntário') </th>
                                    <th>@sortablelink('impeded', 'Impedido') </th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bonds as $bond)
                                    @php
                                        $impediments = $bond->impediments;
                                        $lastReviewDate = null;
                                        if ($impediments->count() > 0) {
                                            $lastImpediment = $impediments->sortByDesc('updated_at')->first();
                                            $lastReviewDate = $lastImpediment->closed_at ?? $lastImpediment->created_at;
                                        }
                                    @endphp
                                    <tr>
                                        <td>
                                            {{  preg_replace('~(\d{3})(\d{3})(\d{3})(\d{2})~', '$1.$2.$3-$4', $bond->employee_cpf) }}
                                        </td>
                                        <td data-bs-html="true" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="bottom" 
                                            data-bs-content="
                                                <strong>Edital: </strong>{{ $bond->hiring_process }} |
                                                <strong>Início: </strong>{{ \Carbon\Carbon::parse($bond->begin)->isoFormat('DD/MM/Y') }} | 
                                                <strong>Encerrado: </strong>{{ isset($bond->terminated_at) ? \Carbon\Carbon::parse($bond->terminated_at)->isoFormat('DD/MM/Y') : '-' }} | 
                                                <strong>Verificado: </strong>{{ isset($lastReviewDate) ? \Carbon\Carbon::parse($lastReviewDate)->isoFormat('DD/MM/Y') : '-' }}
                                            ">
                                            {{ $bond->employee_name }}</td>
                                        <td data-bs-html="true" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="bottom" 
                                            data-bs-content="
                                                <strong>Edital: </strong>{{ $bond->hiring_process }} |
                                                <strong>Início: </strong>{{ \Carbon\Carbon::parse($bond->begin)->isoFormat('DD/MM/Y') }} | 
                                                <strong>Encerrado: </strong>{{ isset($bond->terminated_at) ? \Carbon\Carbon::parse($bond->terminated_at)->isoFormat('DD/MM/Y') : '-' }} | 
                                                <strong>Verificado: </strong>{{ isset($lastReviewDate) ? \Carbon\Carbon::parse($lastReviewDate)->isoFormat('DD/MM/Y') : '-' }}
                                            ">
                                            {{ $bond->role_name }}
                                        </td>
                                        <td>{{ $bond->course_name ?? '-' }}</td>
                                        <td>{{ $bond->pole_name ?? '-' }}</td>
                                        <td>{{ $bond->volunteer === 1 ? 'Sim' : 'Não' }}</td>
                                        <td>{{ $bond->last_open_impediment_date == null ? 'Não' : 'Sim' }}</td>
                                        <td class="text-center"><div class="d-inline-flex">
                                            @can('bond-show')
                                                <a href="{{ route('bonds.show', $bond) }}" data-bs-toggle="tooltip" title="Ver Vínculo" class="btn btn-primary btn-sm">
                                                    <i class="bi-eye-fill"></i>
                                                </a>&nbsp; 
                                            @endcan
                                            @can('employee-show')
                                                <a href="{{ route('employees.show', $bond->employee) }}" data-bs-toggle="tooltip" title="Ver Colaborador" class="btn btn-primary btn-sm">
                                                    <i class="bi-person-fill"></i>
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
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <br />
                    {!! $bonds->links() !!}
                    <a href="{{ route('home') }}" class="btn btn-secondary">Voltar para o Início</a>
                    @can('bond-create')
                        <a href="{{ route('bonds.create') }}" class="btn btn-warning">Cadastrar novo Vínculo</a>
                    @endcan
                    <br /><br />
                </div>
            </div>
        </main>
    </section>
@endsection

@section('scripts')
    @component('_components.filters_script', ['filters' =>$filters] )@endcomponent
    @vite('resources/js/enable_popover.ts')
@endsection