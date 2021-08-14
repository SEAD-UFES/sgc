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
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <span style="color: green; font-weight: bold">{{ $message }}</span>
                </div>
            @endif

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
            <br /><br />
        </main>
    </section>
@endsection

@section('scripts')
    @component('_components.filters_script', ['filters' => $filters])@endcomponent
@endsection
