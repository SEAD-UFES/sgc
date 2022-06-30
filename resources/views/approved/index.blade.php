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
                                [ 'label'=>'Telefone', 'value'=>'phoneContains'],
                                [ 'label'=>'Celular', 'value'=>'mobileContains'],
                                [ 'label'=>'Edital', 'value'=>'announcementContains'],
                                [ 'label'=>'Situação', 'value'=>'approvedStateNameContains'],
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
                                <th>@sortablelink('phone', 'Telefone')</th>
                                <th>@sortablelink('mobile', 'Celular')</th>
                                <th>@sortablelink('announcement', 'Edital')</th>
                                <th>@sortablelink('approvedState.name', 'Situação')</th>
                                <th>@sortablelink('role.name', 'Função')</th>
                                <th>@sortablelink('course.name', 'Curso')</th>
                                <th>@sortablelink('pole.name', 'Polo')</th>
                                <th class="text-center">Mudar Situação</th>
                            </thead>
                            <tbody>
                                @foreach ($approveds as $approved)
                                    <tr>
                                        <td>{{ $approved->name }}</td>
                                        <td><a href="mailto:{{ $approved->email }}">{{ $approved->email }}</a></td>
                                        <td><a href="tel:{{ $approved->phone }}">{{ preg_replace('~(\d{2})[^\d]{0,7}(\d{4})[^\d]{0,7}(\d{4})~', '($1) $2-$3', $approved->phone) }}</a></td>
                                        <td><a href="tel:{{ $approved->mobile }}">{{ preg_replace('~(\d{2})[^\d]{0,7}(\d{5})[^\d]{0,7}(\d{4})~', '($1) $2-$3', $approved->mobile) }}</a></td>
                                        <td>{{ $approved->announcement }}</td>
                                        <td data-bs-toggle="tooltip" data-bs-placement="left" title="{{ $approved->approvedState->description ?? '' }}">{!! $approved->approvedState->name ?? '&nbsp;' !!}</td>
                                        <td>{!! $approved->role->name ?? '&nbsp;' !!}</td>
                                        <td>{!! $approved->course->name ?? '&nbsp;' !!}</td>
                                        <td>{!! $approved->pole->name ?? '&nbsp;' !!}</td>
                                        <td>
                                            <div class="d-inline-flex">
                                                @can('approved-update-state')
                                                    <form name="{{ 'formChangeState' . $approved->id }}" action={{ route('approveds.update', $approved) }} method="POST">
                                                        @method('PATCH')
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
                                                        <span onclick="{{ 'if(confirm(\'Tem certeza que deseja nomear esse Aprovado para Colaborador?\')) window.open("' . route('approveds.designate', $approved) . '")' }}" data-bs-toggle="tooltip" title="Converter o aprovado em Colaborador" class="btn btn-warning btn-sm">Nomear</span>
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
                    <a href="{{ route('home') }}" class="btn btn-secondary">Voltar para o Início</a>
                    @can('approved-store')
                        <a href="{{ route('approveds.create.step1') }}" class="btn btn-warning">Importar novos Aprovados</a>
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