@extends('layouts.basic')

@section('title', 'Cadastrar Funcionário')

@section('content')
    <script>
        function submitAndImport() {
            let element = document.getElementById('importDocuments');
            element.value = "true";

            document.forms['formCreate'].submit();
        }
    </script>

    <section>
        <h2>Cadastrar Colaborador</h2>
    </section>
    <section id="pageContent">
        <main role="main">
            <form action={{ route('employees.store') }} name="formCreate" method="POST">
                @component('employee.componentEmployeeForm', compact('genders', 'birthStates', 'documentTypes',
                'maritalStatuses', 'addressStates', 'employee'))@endcomponent
                <input type="hidden" name="importDocuments" id="importDocuments" value="false" />
                <button type="submit" class="btn btn-primary">Cadastrar</button>
                <button type="button" onclick="submitAndImport();" class="btn btn-primary">Cadastrar e importar
                    documentos</button>
                <button type="button" onclick="history.back()" class="btn btn-secondary">Cancelar</button>
                @error('noStore')
                    <div class="error">> {{ $message }}</div>
                @enderror
                <br /><br />
            </form>
        </main>
    </section>
@endsection
