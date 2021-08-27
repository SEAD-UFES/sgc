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
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <span style="color: green; font-weight: bold">{{ $message }}</span>
                        </div>
                    @endif

                    {{-- filtros --}}
                    @component(
                        '_components.filters_form', 
                        [
                            'filters' =>$filters,
                            'options' => [
                                [ 'label'=>'CPF', 'value'=>'employee_cpf_contains', 'selected'=>true],
                                [ 'label'=>'Colaborador', 'value'=>'employee_name_contains'],
                                [ 'label'=>'Atribuição', 'value'=>'role_name_contains'],
                                [ 'label'=>'Curso', 'value'=>'course_name_contains'],
                                [ 'label'=>'Polo', 'value'=>'pole_name_contains'],
                                [ 'label'=>'Voluntário', 'value'=>'volunteer_exactly'],
                                [ 'label'=>'Impedido', 'value'=>'impediment_exactly'],
                            ]
                        ]
                    )@endcomponent

                    <p style="color: red"> Clique no Nome ou Atribuição para exibir/ocultar as informações de datas</p>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>@sortablelink('employee.cpf', 'CPF')</th>
                                    <th>@sortablelink('employee.name', 'Colaborador')</th>
                                    <th>@sortablelink('role.name', 'Atribuição')</th>
                                    <th>@sortablelink('course.name', 'Curso')</th>
                                    <th>@sortablelink('pole.name', 'Polo')</th>
                                    <th>@sortablelink('volunteer', 'Voluntário') </th>
                                    <th>@sortablelink('impediment', 'Impedido') </th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bonds as $bond)
                                    <tr>
                                        <td>
                                            {{ $bond->employee->cpf }}
                                        </td>
                                        <td data-bs-html="true" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="bottom" 
                                            data-bs-content="
                                                <strong>Início: </strong>{{ isset($bond->begin) ? \Carbon\Carbon::parse($bond->begin)->isoFormat('DD/MM/Y') : '-' }} | 
                                                <strong>Fim: </strong>{{ isset($bond->end) ? \Carbon\Carbon::parse($bond->end)->isoFormat('DD/MM/Y') : '-' }} | 
                                                <strong>Encerrado: </strong>{{ isset($bond->terminated_at) ? \Carbon\Carbon::parse($bond->terminated_at)->isoFormat('DD/MM/Y') : '-' }} | 
                                                <strong>Verificado: </strong>{{ isset($bond->uaba_checked_at) ? \Carbon\Carbon::parse($bond->uaba_checked_at)->isoFormat('DD/MM/Y') : '-' }}
                                            ">
                                            {{ $bond->employee->name }}</td>
                                        <td data-bs-html="true" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="bottom" 
                                            data-bs-content="
                                                <strong>Início: </strong>{{ isset($bond->begin) ? \Carbon\Carbon::parse($bond->begin)->isoFormat('DD/MM/Y') : '-' }} | 
                                                <strong>Fim: </strong>{{ isset($bond->end) ? \Carbon\Carbon::parse($bond->end)->isoFormat('DD/MM/Y') : '-' }} | 
                                                <strong>Encerrado: </strong>{{ isset($bond->terminated_at) ? \Carbon\Carbon::parse($bond->terminated_at)->isoFormat('DD/MM/Y') : '-' }} | 
                                                <strong>Verificado: </strong>{{ isset($bond->uaba_checked_at) ? \Carbon\Carbon::parse($bond->uaba_checked_at)->isoFormat('DD/MM/Y') : '-' }}
                                            ">
                                            {{ $bond->role->name }}
                                        </td>
                                        <td>{{ $bond->course->name }}</td>
                                        <td>{{ $bond->pole->name }}</td>
                                        <td>{{ $bond->volunteer === 1 ? 'Sim' : 'Não' }}</td>
                                        <td>{{ $bond->impediment === 1 ? 'Sim' : 'Não' }}</td>
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
                                            @can('bond-update')
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
                    <button type="button" onclick="history.back()" class="btn btn-secondary">Voltar</button>
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