@extends('layouts.basic')

@section('title', 'Revisão de Importação')

@section('content')
    <section>
        <h2>Revisão de Importação (Colaborador: {{ $fileSet->first()->employee->name }})</h2>
    </section>
    <section id="pageContent">
        <main role="main">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p style="color: green; font-weight: bold">{{ $message }}</p>
                </div><br />
            @endif
            <br />
            <form action={{ route('employees.document.mass.store') }} method="POST">
                @csrf
                <input type="hidden" name="fileSetCount" value="{{ count($fileSet) }}">
                <input type="hidden" name="employeeId" value="{{ $fileSet->first()->employee->id }}">
                <table class="table table-striped table-hover">
                    <thead>
                        <th>Nome do arquivo</th>
                        <th>Tipo de documento</th>
                    </thead>
                    <tbody>
                        @foreach ($fileSet as $key => $file)
                            <input type="hidden" name="fileName_{{ $key }}" value="{{ $file->original_name }}">
                            <input type="hidden" name="filePath_{{ $key }}" value="{{ $file->filePath }}" />
                            <tr>
                                <td title="{{ $file->original_name }}">{{ $file->original_name }}</td>

                                <td><select name="documentTypes_{{ $key }}" id="documentTypes_{{ $key }}" class="form-select">
                                        <option value="">Selecione o tipo de documento</option>
                                        @foreach ($documentTypes as $documentType)
                                            <option value="{{ $documentType->id }}">
                                                {{ $documentType->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <br />
                <button type="submit" class="btn btn-primary">Importar</button> <button type="button" onclick="history.back()" class="btn btn-secondary">Cancelar</button>
            </form>
        </main>
    </section>
@endsection
