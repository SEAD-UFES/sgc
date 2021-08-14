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
            <li class="breadcrumb-item"><a href="{{ route('approveds.index') }}">Listar Aprovados</a></li>
            <li class="breadcrumb-item active" aria-current="page">Nomear aprovado: {{ $employee->name }}</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <form action={{ route('employees.store') }} name="formCreate" method="POST">
                @method('POST')
                @component('employee.componentEmployeeForm', compact('genders', 'birthStates', 'documentTypes',
                'maritalStatuses', 'addressStates', 'employee'))@endcomponent
                <input type="hidden" name="importDocuments" id="importDocuments" value="false" />
                <button type="submit">Cadastrar</button>
                <button type="button" onclick="submitAndImport();">Cadastrar e importar
                    documentos</button>
                <button type="button" onclick="history.back()">Cancelar</button>
                @error('noStore')
                    <div class="error">> {{ $message }}</div>
                @enderror
                <br /><br />
            </form>
        </main>
    </section>
@endsection
