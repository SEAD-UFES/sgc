@extends('layouts.basic')

@section('title', 'Revisão de Importação')

@section('content')
    <section>
        <strong>Revisão de Importação (Colaborador: {{ $fileSet->first()->employee->name }})</strong>
    </section>
    <section id="pageContent">
        <main role="main" style="overflow-x:auto;">
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
                <table style="border: none">
                    <thead>
                        <th style="width:115px;">Nome do arquivo</th>
                        <th style="width:115px">Tipo de documento</th>
                    </thead>
                    <tbody>
                        @foreach ($fileSet as $key => $file)
                            <input type="hidden" name="fileName_{{ $key }}" value="{{ $file->original_name }}">
                            <input type="hidden" name="filePath_{{ $key }}" value="{{ $file->filePath }}" />
                            <tr>
                                <td style="overflow:hidden; white-space:nowrap; padding: 0px;"
                                    title="{{ $file->original_name }}">{{ $file->original_name }}</td>

                                <td style="overflow:hidden; white-space:nowrap; padding: 0px; border: none;"><select
                                        name="documentTypes_{{ $key }}" id="documentTypes_{{ $key }}"
                                        style="width:100%;">
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
                <br /><br />
                <button type="submit">Importar</button> <button type="button" onclick="history.back()">Cancelar</button>
            </form>
            <br /><br />
        </main>
    </section>
@endsection
