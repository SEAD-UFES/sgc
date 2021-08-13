@extends('layouts.basic')

@section('title', 'Home')

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb border-top border-bottom bg-light">
        <li class="breadcrumb-item active" aria-current="page">
            Home&nbsp;
            @if (session('sessionUser')->currentBond != null)
                [{{ session('sessionUser')->currentBond->role->name }} -
                {{ session('sessionUser')->currentBond->course->name }} -
                {{ session('sessionUser')->currentBond->pole->name }}]
            @endif
        </li>
    </ol>
</nav>
<section id="pageContent">
    <main role="main">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-xxl-6">
                <h3>Notificações</h3>
                <br />
                <table class="table table-striped table-hover mx-1">
                    <thead>
                        <tr>
                            <th>Envio</th>
                            <th>Mensagem</th>
                            <th>Dispensar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (auth()->user()->notifications->count() < 1)
                            <tr>
                                <td colspan="3" class="text-center">Sem notificações</td>
                            </tr>
                        @endif
                        @foreach (auth()->user()->notifications as $notification)
                            <tr>
                                <td class="align-middle">{{ \Carbon\Carbon::parse($notification->created_at)->isoFormat('DD/MM/Y hh:mm') }}
                                </td>
                                @switch($notification->type)
                                    @case('App\Notifications\NewBondNotification')
                                        <td><strong>= Novo <a
                                                    href="{{ route('bonds.show', $notification->data['bond_id']) }}">vínculo</a>
                                                cadastrado =</strong><br />
                                            Colaborador: {{ $notification->data['employee_name'] }}<br />
                                            Atribuição: {{ $notification->data['role_name'] }} |
                                            Curso: {{ $notification->data['course_name'] }}
                                        </td>
                                    @break
                                    @case('App\Notifications\BondImpededNotification')
                                        <td><strong>= <a
                                                    href="{{ route('bonds.show', $notification->data['bond_id']) }}">Vínculo</a>
                                                impedido =</strong><br />
                                            Colaborador: {{ $notification->data['employee_name'] }}<br />
                                            Atribuição: {{ $notification->data['role_name'] }} |
                                            Curso: {{ $notification->data['course_name'] }}<br />
                                            Motivo: {{ $notification->data['description'] }}
                                        </td>
                                    @break
                                    @case('App\Notifications\NewRightsNotification')
                                        <td><strong>= Novo <a
                                                    href="{{ route('documents.show', ['id' => $notification->data['document_id'], 'type' => 'BondDocument', 'htmlTitle' => $notification->data['document_name']]) }}"
                                                    target="_blank">Documento de Termos e Licença</a> =</strong><br />
                                            Colaborador: {{ $notification->data['employee_name'] }}<br />
                                            Atribuição: {{ $notification->data['role_name'] }} |
                                            Curso: {{ $notification->data['course_name'] }}<br />
                                            <a href="{{ route('bonds.rights.index') }}" target="_blank">[Listar
                                                Documentos de Termos e Licença]</a>
                                        </td>
                                    @break
                                    @case('App\Notifications\RequestReviewNotification')
                                        <td><strong>= Solicitação de nova Revisão =</strong><br />
                                            Vínculo: <a
                                                href="{{ route('bonds.show', $notification->data['bond_id']) }}">{{ $notification->data['employee_name'] . '-' . $notification->data['role_name'] . '-' . $notification->data['course_name'] }}</a><br />
                                            Colaborador: {{ $notification->data['employee_name'] }}<br />
                                            Atribuição: {{ $notification->data['role_name'] }} |
                                            Curso: {{ $notification->data['course_name'] }}<br />
                                            Solicitante: {{ $notification->data['requester_name'] }}
                                        </td>
                                    @break

                                    @default
                                        <td>:(</td>
                                @endswitch
                                <td class="align-middle"><a href="#" class="btn btn-danger btn-sm">Dispensar</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</section>
@endsection
