@extends('layouts.basic')

@section('title', 'Importar Aprovados')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Colaboradores</li>
            <li class="breadcrumb-item active" aria-current="page">Importar Aprovados</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <form action={{ route('approveds.import') }} method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="inputFile1" class="form-label">Selecione o arquivo</label>
                    <input class="form-control" type="file" name="file" id="inputFile1">
                </div>
                <br />
                <button type="submit" class="btn btn-primary">Enviar arquivo</button> <button type="button"
                    onclick="history.back()" class="btn btn-secondary">Cancelar</button>
                @error('noStore')
                    <div class="error">> {{ $message }}</div>
                @enderror
            </form>
            <br />
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-7 col-xl-6 col-xxl-5">
                    <p>Formato mínimo esperado da planilha: Nome, Email, Telefone(s), Edital</p>
                    <p>Ex:</p>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <tr>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Telefone</th>
                                <th>Edital</th>
                            </tr>
                            <tr>
                                <td>Mateus</td>
                                <td>mateus@ufes.br</td>
                                <td>(27) 3339 3554<br />(27) 99999-9375</td>
                                <td>099/2021</td>
                            </tr>
                            <tr>
                                <td>Júlia</td>
                                <td>julia@gmail.com</td>
                                <td>(27) 99999-9374</td>
                                <td>099/2021</td>
                            </tr>
                            <tr>
                                <td>Gabriel</td>
                                <td>gabriel@outlook.com</td>
                                <td>(28) 99999-0647</td>
                                <td>099/2021</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <br />
        </main>
    </section>
@endsection
