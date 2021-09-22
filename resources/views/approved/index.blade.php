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
                                [ 'label'=>'Nome', 'value'=>'name_contains', 'selected'=>true],
                                [ 'label'=>'E-mail', 'value'=>'email_contains'],
                                [ 'label'=>'Área', 'value'=>'areacode_contains'],
                                [ 'label'=>'Telefone', 'value'=>'phone_contains'],
                                [ 'label'=>'Celular', 'value'=>'mobile_contains'],
                                [ 'label'=>'Edital', 'value'=>'announcement_contains'],
                                [ 'label'=>'Situação', 'value'=>'approvedState_name_contains'],
                                [ 'label'=>'Atribuição', 'value'=>'role_name_contains'],
                                [ 'label'=>'Curso', 'value'=>'course_name_contains'],
                                [ 'label'=>'Polo', 'value'=>'pole_name_contains'],

                            ]
                        ]
                    )@endcomponent
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <th>@sortablelink('name', 'Nome')</th>
                                <th>@sortablelink('email', 'E-mail')</th>
                                <th>@sortablelink('area_code', 'Área')</th>
                                <th>@sortablelink('phone', 'Telefone')</th>
                                <th>@sortablelink('mobile', 'Celular')</th>
                                <th>@sortablelink('announcement', 'Edital')</th>
                                <th>@sortablelink('approvedState.name', 'Situação')</th>
                                <th>@sortablelink('role.name', 'Atribuição')</th>
                                <th>@sortablelink('course.name', 'Curso')</th>
                                <th>@sortablelink('pole.name', 'Polo')</th>
                                <th class="text-center">Mudar Situação</th>
                            </thead>
                            <tbody>
                                @foreach ($approveds as $approved)
                                    <tr>
                                        <td>{{ $approved->name }}</td>
                                        <td><a href="mailto:{{ $approved->email }}">{{ $approved->email }}</a></td>
                                        <td>{{ $approved->area_code }}</td>
                                        <td><a href="tel:{{ /* $approved->area_code .  */$approved->phone }}">{{ $approved->phone }}</a></td>
                                        <td><a href="tel:{{ /* $approved->area_code .  */$approved->mobile }}">{{ $approved->mobile }}</a></td>
                                        <td>{{ $approved->announcement }}</td>
                                        <td data-bs-toggle="tooltip" data-bs-placement="left" title="{{ $approved->approvedState->description ?? '' }}">{!! $approved->approvedState->name ?? '&nbsp;' !!}</td>
                                        <td>{!! $approved->role->name ?? '&nbsp;' !!}</td>
                                        <td>{!! $approved->course->name ?? '&nbsp;' !!}</td>
                                        <td>{!! $approved->pole->name ?? '&nbsp;' !!}</td>
                                        <td>
                                            <div class="d-inline-flex">
                                                @can('approved-update-state')
                                                    <form name="{{ 'formChangeState' . $approved->id }}" action={{ route('approveds.changestate', $approved) }} method="POST">
                                                        @csrf
                                                        <select name="states" id="selectState1" class="form-select form-select-sm w-auto" data-bs-toggle="tooltip" data-bs-placement="left" title="{{ $approved->approvedState->description ?? '' }}" onchange="{{ 'document.forms[\'formChangeState' . $approved->id . '\'].submit();' }}">
                                                            @foreach ($approvedStates as $approvedState)
                                                                <option value="{{ $approvedState->id }}" {{ $approvedState->id == $approved->approvedState->id ? 'selected' : '' }}>
                                                                    {{ $approvedState->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </form>
                                                @endcan
                                                @can('approved-designate')
                                                    @if ($approved->approvedState->name == 'Aceitante')
                                                        &nbsp;
                                                        <form name="{{ 'formDesignate' . $approved->id }}" action={{ route('approveds.designate', $approved) }} method="POST">
                                                            @csrf
                                                            <span onclick="{{ 'if(confirm(\'Tem certeza que deseja nomear esse Aprovado para Colaborador?\')) document.forms[\'formDesignate' . $approved->id . '\'].submit();' }}" data-bs-toggle="tooltip" title="Converter o aprovado em Colaborador" class="btn btn-warning btn-sm">Nomear</span>
                                                        </form>
                                                    @endif
                                                @endcan
                                                @can('approved-destroy')
                                                    @if ($approved->approvedState->name == 'Desistente')
                                                        &nbsp;
                                                        <form name="{{ 'formDestroy' . $approved->id }}" action={{ route('approveds.destroy', ['approved' => $approved]) }} method="POST">
                                                            @method('DELETE')
                                                            @csrf
                                                            <span onclick="{{ 'if(confirm(\'Tem certeza que deseja remover esse Aprovado da listagem?\')) document.forms[\'formDestroy' . $approved->id . '\'].submit();' }}" data-bs-toggle="tooltip" title="Remover o aprovado desistente da listagem" class="btn btn-danger btn-sm">Remover</span>
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
                    {!! $approveds->links() !!}
                    <button type="button" onclick="history.back()" class="btn btn-secondary">Voltar</button>
                    @can('approved-store')
                        <a href="{{ route('approveds.create') }}" class="btn btn-warning">Importar novos Aprovados</a>
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