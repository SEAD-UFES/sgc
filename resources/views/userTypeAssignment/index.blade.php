@extends('layouts.basic')

@section('title', 'Atribuições de papel')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Sistema</li>
            <li class="breadcrumb-item active" aria-current="page">Listar Atribuições de Papel</li>
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
                                ['label' => 'Usuário', 'value' => 'userEmailContains', 'selected' => true], 
                                ['label' => 'Papel', 'value' => 'usertypeNameContains'],
                                ['label' => 'Curso', 'value' => 'courseNameContains'], 
                                ['label' => 'Início (=)', 'value'=>'beginExactly'],
                                ['label' => 'Início (>=)' , 'value'=>'beginBigOrEqu'],
                                ['label' => 'Início (<=)', 'value'=>'beginLowOrEqu'],
                                ['label' => 'Fim (=)', 'value'=>'endExactly'],
                                ['label' => 'Fim (>=)' , 'value'=>'endBigOrEqu'],
                                ['label' => 'Fim (<=)', 'value'=>'endLowOrEqu'],
                            ],
                        ]
                    )@endcomponent
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <th>@sortablelink('user.email', 'Usuário')</th>
                                <th>@sortablelink('userType.name', 'Papel')</th>
                                <th>@sortablelink('begin', 'Início')</th>
                                <th>@sortablelink('end', 'Fim')</th>
                                <th class="text-center">Ações</th>
                            </thead>
                            <tbody>
                                @foreach ($userTypeAssignments as $userTypeAssignment)
                                    <tr>
                                        <td>{{ $userTypeAssignment->user->email }}</td>
                                        <td>{{ $userTypeAssignment->userType->name }} {{ $userTypeAssignment->course ? "(".$userTypeAssignment->course->name.")": '' }}</td>
                                        <td>{{ $userTypeAssignment->begin }}</td>
                                        <td>{{ $userTypeAssignment->end ?? "-" }}</td>
                                        <td class="text-center"><div class="d-inline-flex">
                                            @can('userTypeAssignment-update')
                                                <a href="{{ route('userTypeAssignments.edit', $userTypeAssignment) }}" data-bs-toggle="tooltip" title="Editar usuário" class="btn btn-primary btn-sm">
                                                    <i class="bi-pencil-fill"></i>
                                                </a>&nbsp;          
                                            @endcan
                                            @can('userTypeAssignment-destroy')
                                                <form name="{{ 'formDelete' . $userTypeAssignment->id }}" action="{{ route('userTypeAssignments.destroy', $userTypeAssignment) }}" method="POST">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="button" data-bs-toggle="tooltip" title="Excluir usuário" 
                                                        onclick="{{ 'if(confirm(\'Tem certeza que deseja excluir esse usuário?\')) document.forms[\'formDelete' . $userTypeAssignment->id . '\'].submit();' }}" class="btn btn-danger btn-sm">
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
                    {!! $userTypeAssignments->links() !!}

                    @if(sizeof($userTypeAssignments) <= 0)
                        <p>Sem resultados para exibir.</p>
                        <br />
                    @endif
                    <a href="{{ route('home') }}" class="btn btn-secondary">Voltar para o Início</a>
                    @can('isAdm-global')
                        <a href="{{ route('userTypeAssignments.create') }}" class="btn btn-warning">Cadastrar nova Atrib. de Papel</a>
                    @endcan
                    <br />
                </div>
            </div>
        </main>
    </section>
@endsection

@section('scripts')
    @component('_components.filters_script', ['filters' => $filters])@endcomponent
    <script src="{{ asset('js/enable_tooltip_popover.js') }}"></script>
@endsection
