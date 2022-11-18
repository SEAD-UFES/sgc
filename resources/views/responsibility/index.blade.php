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
                                ['label' => 'Usuário', 'value' => 'userLoginContains', 'selected' => true], 
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
                                <th>@sortablelink('user.login', 'Usuário')</th>
                                <th>@sortablelink('userType.name', 'Papel')</th>
                                <th>@sortablelink('course.name', 'Curso')</th>
                                <th>@sortablelink('begin', 'Início')</th>
                                <th>@sortablelink('end', 'Fim')</th>
                                <th class="text-center">Ações</th>
                            </thead>
                            <tbody>
                                @foreach ($responsibilities as $responsibility)
                                    <tr>
                                        <td>{{ $responsibility->user->login }}</td>
                                        <td>{{ $responsibility->userType->name }} {{ $responsibility->course ? "(".$responsibility->course->name.")": '' }}</td>
                                        <td>{{ $responsibility->course ? $responsibility->course->name : '-' }}</td>
                                        <td>{{ isset($responsibility->begin) ? \Carbon\Carbon::parse($responsibility->begin)->isoFormat('DD/MM/Y') : '-' }}</td>
                                        <td>{{ isset($responsibility->end) ? \Carbon\Carbon::parse($responsibility->end)->isoFormat('DD/MM/Y') : '-' }}</td>
                                        <td class="text-center"><div class="d-inline-flex">
                                            @can('responsibility-update')
                                                <a href="{{ route('responsibilities.edit', $responsibility) }}" data-bs-toggle="tooltip" title="Editar usuário" class="btn btn-primary btn-sm">
                                                    <i class="bi-pencil-fill"></i>
                                                </a>&nbsp;          
                                            @endcan
                                            @can('responsibility-destroy')
                                                <form name="{{ 'formDelete' . $responsibility->id }}" action="{{ route('responsibilities.destroy', $responsibility) }}" method="POST">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="button" data-bs-toggle="tooltip" title="Excluir usuário" 
                                                        onclick="{{ 'if(confirm(\'Tem certeza que deseja excluir esse usuário?\')) document.forms[\'formDelete' . $responsibility->id . '\'].submit();' }}" class="btn btn-danger btn-sm">
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
                    {!! $responsibilities->links() !!}

                    @if(sizeof($responsibilities) <= 0)
                        <p>Sem resultados para exibir.</p>
                        <br />
                    @endif
                    <a href="{{ route('home') }}" class="btn btn-secondary">Voltar para o Início</a>
                    @can('isAdm-global')
                        <a href="{{ route('responsibilities.create') }}" class="btn btn-warning">Cadastrar nova Atrib. de Papel</a>
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
