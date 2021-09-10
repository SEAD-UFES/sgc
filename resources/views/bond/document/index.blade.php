@extends('layouts.basic')

@section('title', 'Documentos de Vínculos')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Colaboradores</li>
            <li class="breadcrumb-item active" aria-current="page">Listar Documentos de Vínculos</li>
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
                                ['label' => 'Colaborador', 'value' => 'bond_employee_name_contains', 'selected' => true], 
                                ['label' => 'Cargo', 'value' => 'bond_role_name_contains'], 
                                ['label' => 'Curso', 'value' => 'bond_course_name_contains'], 
                                ['label' => 'Nome do arquivo', 'value' => 'originalname_contains'], 
                                ['label' => 'Tipo do documento', 'value' => 'documentType_name_contains']
                            ], 
                        ]
                    )@endcomponent

                    @component('bond.document.componentList', compact('documents'))@endcomponent
                    <button type="button" onclick="history.back()" class="btn btn-secondary">Voltar</button>
                    @can('approved-store')
                        <a href="{{ route('bonds.document.create') }}" class="btn btn-warning">Importar Documento de Vínculo</a>
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
