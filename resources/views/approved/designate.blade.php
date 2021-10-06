@extends('layouts.basic')

@section('title', 'Nomear Aprovado')

@section('content')
    <script>
        function submitAndImport() {
            let element = document.getElementById('importDocuments');
            element.value = "true";

            document.forms['formCreate'].submit();
        }
    </script>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Colaboradores</li>
            <li class="breadcrumb-item active" aria-current="page">Nomear aprovado: {{ $employee->name }}</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-xxl-8">
                    @include('_components.alerts')
                    <form action={{ route('employees.store') }} name="formCreate" method="POST">
                        @component('employee.componentEmployeeForm', compact('genders', 'birthStates', 'documentTypes',
                        'maritalStatuses', 'addressStates', 'employee'))@endcomponent
                        <input type="hidden" name="importDocuments" id="importDocuments" value="false" />
                        <br />
                        <button type="submit" class="btn btn-primary">Cadastrar</button>
                        <button type="button" onclick="submitAndImport();" class="btn btn-primary">Cadastrar e importar
                            documentos</button>
                        <button type="button" onclick="history.back()" class="btn btn-secondary">Cancelar</button>
                        @error('noStore')
                            <div class="text-danger">> {{ $message }}</div>
                        @enderror
                        <br /><br />
                    </form>
                </div>
            </div>
        </main>
    </section>
@endsection
