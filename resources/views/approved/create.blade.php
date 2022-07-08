@extends('layouts.basic')

@section('title', 'Cadastrar Aprovado')

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
            <li class="breadcrumb-item active" aria-current="page">Cadastrar Aprovado</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-xxl-8">
                    @include('_components.alerts')
                    <form action={{ route('approveds.store') }} name="formCreate" method="POST">
                        @component('approved.componentApprovedForm', compact('courses', 'roles', 'poles'))@endcomponent
                        <input type="hidden" name="importDocuments" id="importDocuments" value="false" />
                        <br />
                        <button type="submit" class="btn btn-primary">Cadastrar</button>
                        <a href="{{ route('approveds.index') }}" class="btn btn-secondary">Cancelar</a>
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
