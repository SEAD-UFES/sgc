@extends('layouts.basic')

@section('title', 'Listar Aprovados')

@section('content')
    <section>
        <strong>Listar Aprovados</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p style="color: green; font-weight: bold">{{ $message }}</p>
                </div><br />
            @endif
            <table style="border: 0px">
                <thead>
                    <th style="width:75px;">Nome</th>
                    <th style="width:110px;">E-mail</th>
                    <th style="width:20px;">Área</th>
                    <th style="width:48px;">Telefone</th>
                    <th style="width:53px;">Celular</th>
                    <th style="width:35px;">Edital</th>
                    <th style="width:31px;">Status</th>
                    <th style="width:45px;">Atribuição</th>
                    <th style="width:40px;">Curso</th>
                    <th style="width:31px;">Polo</th>
                    <th style="width:41px;" colspan="2">Mudar Status</th>
                </thead>
                <tbody>
                    @foreach ($approveds as $approved)
                        <tr>
                            <td style="padding: 1px;">{{ $approved->name }}</td>
                            <td style="overflow:hidden; padding: 1px;">{{ $approved->email }}</td>
                            <td style="overflow:hidden; padding: 1px;">{{ $approved->area_code }}</td>
                            <td style="overflow:hidden; padding: 1px;">{{ $approved->phone }}</td>
                            <td style="overflow:hidden; padding: 1px;">{{ $approved->mobile }}</td>
                            <td style="overflow:hidden; padding: 1px;">{{ $approved->announcement }}</td>
                            <td style="overflow:hidden; padding: 1px;" title="{{ $approved->approvedState->description ?? '' }}">{!! $approved->approvedState->name ?? '&nbsp;' !!}</td>
                            <td style="overflow:hidden; padding: 1px;">{!! $approved->role->name ?? '&nbsp;' !!}</td>
                            <td style="overflow:hidden; padding: 1px;">{!! $approved->course->name ?? '&nbsp;' !!}</td>
                            <td style="overflow:hidden; padding: 1px;">{!! $approved->pole->name ?? '&nbsp;' !!}</td>
                            @if ($approved->approvedState->hasNext())
                                @foreach ($approved->approvedState->getNext() as $state)
                                    <td title="{{ $state->description }}" @if ($approved->approvedState->getNext()->count() == 1) colspan="2" @endif style="text-align: center;">
                                        <a href="{{ route('approveds.changestate', ['approved' => $approved, 'state' => $state->id]) }}">{{ $state->name }}</a>
                                    </td>
                                @endforeach
                            @else
                                <td colspan="2" style="text-align: center;">
                                    @if ($approved->approvedState->name == 'Aceitante')
                                        <form name="{{ 'formDesignate' . $approved->id }}"
                                            action={{ route('approveds.designate') }} method="POST">
                                            @csrf
                                            <input type="hidden" name="approvedId" value="{{ $approved->id }}" />
                                            <span title="Converter o aprovado em Colaborador"
                                                onclick="{{ 'document.forms[\'formDesignate' . $approved->id . '\'].submit();' }}"
                                                style="cursor:pointer; color:blue; text-decoration:underline;">Nomeado</span>
                                        </form>
                                    @else
                                        Não existe próximo Status
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {!! $approveds->links() !!}
            <br />
        </main>
    </section>
@endsection
