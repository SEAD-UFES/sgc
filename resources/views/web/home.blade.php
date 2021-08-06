@extends('layouts.basic')

@section('title', 'Home')

@section('content')
    <section>
        <h2>Home&nbsp;
            @if (session('sessionUser')->currentBond != null)
                [{{ session('sessionUser')->currentBond->role->name }} -
                {{ session('sessionUser')->currentBond->course->name }} -
                {{ session('sessionUser')->currentBond->pole->name }}]
            @endif
        </h2>
    </section>
    <section id="pageContent">
        <main role="main">
            <h3>Notificações</h3>
            <br />
            <table class="table table-striped table-hover mx-1">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Mensagem</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (auth()->user()->notifications as $notification)
                        <tr>
                            <td>{{ $notification->created_at }}</td>
                            @switch($notification->type)
                                @case('App\Notifications\NewBondNotification')
                                    <td><strong>= Novo <a href="{{ route('bonds.show', $notification->data['bond_id']) }}">vínculo</a> cadastrado =</strong><br />
                                        Colaborador: {{ $notification->data['employee_name'] }}<br />
                                        Atribuição: {{ $notification->data['role_name'] }} |
                                        Curso: {{ $notification->data['course_name'] }}
                                    </td>
                                @break
                                @case('App\Notifications\BondImpededNotification')
                                    <td><strong>= <a href="{{ route('bonds.show', $notification->data['bond_id']) }}">Vínculo</a> impedido =</strong><br />
                                        Colaborador: {{ $notification->data['employee_name'] }}<br />
                                        Atribuição: {{ $notification->data['role_name'] }} |
                                        Curso: {{ $notification->data['course_name'] }}<br />
                                        Motivo: {{ $notification->data['description'] }}
                                    </td>
                                @break
                                @case('App\Notifications\NewRightsNotification')
                                    <td><strong>= Novo <a href="{{ route('documents.show', ['id' => $notification->data['document_id'], 'type' => 'BondDocument', 'htmlTitle' => $notification->data['document_name']]) }}" target="_blank">Documento de Termos e Licença</a> =</strong><br />
                                        Colaborador: {{ $notification->data['employee_name'] }}<br />
                                        Atribuição: {{ $notification->data['role_name'] }} |
                                        Curso: {{ $notification->data['course_name'] }}<br />
                                        <a href="{{ route('bonds.rights.index') }}" target="_blank">[Listar Documentos de Termos e Licença]</a>
                                    </td>
                                @break

                                @default
                                    <td>:(</td>
                            @endswitch
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <br />
        </main>
    </section>
@endsection
