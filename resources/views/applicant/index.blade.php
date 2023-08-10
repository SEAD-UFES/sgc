@extends('layouts.basic')

@section('title', 'Aprovados')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Colaboradores</li>
            <li class="breadcrumb-item active" aria-current="page">Listar Aprovados</li>
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
                                [ 'label'=>'Nome', 'value'=>'nameContains', 'selected'=>true],
                                [ 'label'=>'E-mail', 'value'=>'emailContains'],
                                [ 'label'=>'Área', 'value'=>'areacodeContains'],
                                [ 'label'=>'Telefone', 'value'=>'landlineContains'],
                                [ 'label'=>'Celular', 'value'=>'mobileContains'],
                                [ 'label'=>'Edital', 'value'=>'hiringprocessContains'],
                                [ 'label'=>'Situação', 'value'=>'callStateLabelContains'],
                                [ 'label'=>'Função', 'value'=>'roleNameContains'],
                                [ 'label'=>'Curso', 'value'=>'courseNameContains'],
                                [ 'label'=>'Polo', 'value'=>'poleNameContains'],

                            ]
                        ]
                    )@endcomponent
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <th>@sortablelink('name', 'Nome')</th>
                                <th>@sortablelink('email', 'E-mail')</th>
                                <th>@sortablelink('landline', 'Telefone')</th>
                                <th>@sortablelink('mobile', 'Celular')</th>
                                <th>@sortablelink('hiring_process', 'Edital')</th>
                                <th>@sortablelink('call_state', 'Situação')</th>
                                <th>@sortablelink('role.name', 'Função')</th>
                                <th>@sortablelink('course.name', 'Curso')</th>
                                <th>@sortablelink('pole.name', 'Polo')</th>
                                <th class="text-center">Mudar Situação</th>
                            </thead>
                            <tbody>
                                @foreach ($applicants as $applicant)
                                    <tr>
                                        <td>{{ $applicant->name }}</td>
                                        <td><a href="mailto:{{ $applicant->email }}">{{ $applicant->email }}</a></td>
                                        @if ($applicant->landline != '')
                                            <td><a href="tel:{{ $applicant->landline }}">{{ $applicant->landline }}</a></td>
                                        @else
                                            <td>-</td>
                                        @endif
                                        <td><a href="tel:{{ $applicant->mobile }}">{{ $applicant->mobile }}</a></td>
                                        <td>{{ $applicant->hiring_process }}</td>
                                        <td data-bs-toggle="tooltip" data-bs-placement="left" title="{{ $applicant->call_state->description() }}">{{ $applicant->call_state->label() }}</td>
                                        <td>{{ $applicant->role->name }}</td>
                                        <td>{{ $applicant->course?->name ?? '-' }}</td>
                                        <td>{{ $applicant->pole?->name ?? '-' }}</td>
                                        <td>
                                            <div class="d-inline-flex">
                                                @can('applicant-update-state')
                                                    <form name="{{ 'formChangeState' . $applicant->id }}" action={{ route('applicants.update', $applicant) }} method="POST">
                                                        @method('PATCH')
                                                        @csrf
                                                        <select name="states" id="selectState1" class="form-select form-select-sm w-auto" data-bs-toggle="tooltip" data-bs-placement="left" title="{{ $applicant->call_state->description() ?? '' }}" onchange="{{ 'document.forms[\'formChangeState' . $applicant->id . '\'].submit();' }}">
                                                            @foreach ($applicantStates as $applicantState)
                                                                <option value="{{ $applicantState->name }}" {{ $applicantState->name == $applicant->call_state->name ? 'selected' : '' }}>
                                                                    {{ $applicantState->label() }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </form>
                                                @endcan
                                                @can('applicant-designate')
                                                    @if ($applicant->call_state->label() == 'Aceitante')
                                                        &nbsp;
                                                        <span onclick="{{ 'if(confirm(\'Tem certeza que deseja nomear esse Aprovado para Colaborador?\')) window.open("' . route('applicants.designate', $applicant) . '")' }}" data-bs-toggle="tooltip" title="Converter o aprovado em Colaborador" class="btn btn-warning btn-sm">Nomear</span>
                                                    @endif
                                                @endcan
                                                @can('applicant-destroy')
                                                    @if ($applicant->call_state->label() == 'Desistente')
                                                        &nbsp;
                                                        <form name="{{ 'formDestroy' . $applicant->id }}" action={{ route('applicants.destroy', ['applicant' => $applicant]) }} method="POST">
                                                            @method('DELETE')
                                                            @csrf
                                                            <span onclick="{{ 'if(confirm(\'Tem certeza que deseja remover esse Aprovado da listagem?\')) document.forms[\'formDestroy' . $applicant->id . '\'].submit();' }}" data-bs-toggle="tooltip" title="Remover o aprovado desistente da listagem" class="btn btn-danger btn-sm">Remover</span>
                                                        </form>
                                                    @endif
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <br />
                    {{ $applicants->links() }}
                    <a href="{{ route('home') }}" class="btn btn-secondary">Voltar para o Início</a>
                    @can('applicant-store')
                        <a href="{{ route('applicants.create') }}" class="btn btn-warning">Cadastrar novo Aprovado</a>
                        <a href="{{ route('applicants.create_many.step_1') }}" class="btn btn-warning">Importar planilha de Aprovados</a>
                    @endcan
                    <br /><br />
                </div>
            </div>
        </main>
    </section>
@endsection

@section('scripts')
    @component('_components.filters_script', ['filters' =>$filters] )@endcomponent
@endsection