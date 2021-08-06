@extends('layouts.basic')

@section('title', 'Polos')

@section('content')
    <section>
        <h2>Polos</h2>
    </section>
    <section id="pageContent">
        <main role="main">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p style="color: green; font-weight: bold">{{ $message }}</p>
                </div><br />
            @endif
            <table class="table table-striped table-hover">
                <thead>
                    <th>@sortablelink('name', 'Nome')</th>
                    <th>@sortablelink('description', 'Descrição')</th>
                    <th colspan="2">Ações</th>
                </thead>
                <tbody>
                    @foreach ($poles as $pole)
                        <tr>
                            <td>{{ $pole->name }}</td>
                            <td>{{ $pole->description }}</td>
                            <td><a href="{{ route('poles.edit', $pole) }}">Editar</a></td>
                            <td>
                                <form name="{{ 'formDelete' . $pole->id }}" action="{{ route('poles.destroy', $pole) }}"
                                    method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <span
                                        onclick="{{ 'if(confirm(\'Tem certeza que deseja excluir esse Polo?\')) document.forms[\'formDelete' . $pole->id . '\'].submit();' }}"
                                        style="cursor:pointer; color:blue; text-decoration:underline;">Excluir</span>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {!! $poles->links() !!}
            <br />
            <button type="button" onclick="history.back()" class="btn btn-secondary">Voltar</button>
        </main>
    </section>
@endsection
