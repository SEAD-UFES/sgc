@extends('layouts.basic')

@section('title', 'Listar Polos')

@section('content')
    <section>
        <strong>Listar Polos</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p style="color: green; font-weight: bold">{{ $message }}</p>
                </div><br />
            @endif
            <table>
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
        </main>
    </section>
@endsection
