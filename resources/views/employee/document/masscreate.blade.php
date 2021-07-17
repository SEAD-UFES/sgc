@extends('layouts.basic')

@section('title', 'Importar Documentos de Colaborador')

@section('content')
    <section>
        <strong>Importar Documentos de Colaborador</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <form action={{ route('employees.document.mass.import') }} method="POST" enctype="multipart/form-data">
                @csrf
                Selecione o arquivo: <input type="file" name="files[]" multiple accept="image/*,.pdf">
                <br /><br />
                @if ($errors->has('files'))
                    @foreach ($errors->get('files') as $error)
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $error }}</strong>
                        </span>
                    @endforeach
                @endif
                Colaborador*: <select name="employees">
                    <option value="">Selecione o colaborador</option>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}" {{ $employee->id == $id ? 'selected' : '' }}>
                            {{ $employee->name }}</option>
                    @endforeach
                </select>
                @error('employees')
                    <div class="error">> {{ $message }}</div>
                @enderror
                <br /><br />
                <button type="submit">Enviar arquivos</button> <button type="button"
                    onclick="history.back()">Cancelar</button>
                @error('noStore')
                    <div class="error">> {{ $message }}</div>
                @enderror
                <br /><br /><br />
            </form>
            <br />
            <p>Formato esperado de arquivos: pdf,jpeg,png,jpg</p><br />
            <br />
        </main>
    </section>
@endsection
