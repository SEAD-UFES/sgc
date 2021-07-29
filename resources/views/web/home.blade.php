@extends('layouts.basic')

@section('title', 'Home')

@section('content')
    <section>
        <strong>Home&nbsp;
            @if (session('sessionUser')->currentBond != null)
                [{{ session('sessionUser')->currentBond->role->name }} -
                {{ session('sessionUser')->currentBond->course->name }} -
                {{ session('sessionUser')->currentBond->pole->name }}]
            @endif

        </strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <h3>Notificações</h3>
            <br />
            <table>
                <thead>
                    <tr>
                        <th style="width: 150px">Data</th>
                        <th>Mensagem</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (auth()->user()->notifications as $notification)
                        <tr>
                            <td>{{ $notification->created_at }}</td>
                            @switch($notification->type)
                                @case('App\Notifications\NewBondNotification')
                                    <td style="padding: 3px">Novo <a href="{{ route('bonds.show', $notification->data['bond_id']) }}">vínculo</a> cadastrado |
                                        Colaborador: {{ $notification->data['employee_name'] }}<br />
                                        Atribuição: {{ $notification->data['role_name'] }} |
                                        Curso: {{ $notification->data['course_name'] }}
                                    </td>
                                @break
                                @case('App\Notifications\BondImpededNotification')
                                    <td style="padding: 3px"><a href="{{ route('bonds.show', $notification->data['bond_id']) }}">Vínculo</a> impedido |
                                        Colaborador: {{ $notification->data['employee_name'] }}<br />
                                        Atribuição: {{ $notification->data['role_name'] }} |
                                        Curso: {{ $notification->data['course_name'] }}
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
