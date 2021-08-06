@extends('layouts.basic')

@section('title', 'Documentos de Vínculos')

@section('content')
    <section>
        <h2>Documentos de Vínculos</h2>
    </section>
    <section id="pageContent">
        <main role="main">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p style="color: green; font-weight: bold">{{ $message }}</p>
                </div><br />
            @endif

            {{-- local para os filtros --}}

            @component(
                '_components.filters_form', 
                [
                    'filters' =>$filters,
                    'options' => [
                        [ 'label'=>'Colaborador', 'value'=>'bond_employee_name_contains', 'selected'=>true],
                        [ 'label'=>'Cargo', 'value'=>'bond_role_name_contains' ],
                        [ 'label'=>'Curso', 'value'=>'bond_course_name_contains' ],
                        [ 'label'=>'Nome do arquivo', 'value'=>'originalname_contains' ],
                        [ 'label'=>'Tipo do documento', 'value'=>'documentType_name_contains' ],
                    ]
                ]
            )@endcomponent

            <br/>

            @component('bond.document.componentList', compact('documents'))@endcomponent
            <br />
            <button type="button" onclick="history.back()" class="btn btn-secondary">Voltar</button>
            <br /><br /><br />
        </main>
    </section>
@endsection

@section('scripts')

@component('_components.filters_script', ['filters' =>$filters] )@endcomponent

@endsection