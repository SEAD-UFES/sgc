@extends('layouts.basic')

@section('title', 'Aprovados')

@section('content')
    <section>
        <h2>Aprovados</h2>
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
                    <th>@sortablelink('email', 'E-mail')</th>
                    <th>@sortablelink('area_code', 'Área')</th>
                    <th>@sortablelink('phone', 'Telefone')</th>
                    <th>@sortablelink('mobile', 'Celular')</th>
                    <th>@sortablelink('announcement', 'Edital')</th>
                    <th>@sortablelink('approvedState.description', 'Status')</th>
                    <th>@sortablelink('role.name', 'Atribuição')</th>
                    <th>@sortablelink('course.name', 'Curso')</th>
                    <th>@sortablelink('pole.name', 'Polo')</th>
                    <th colspan="2">Mudar Status</th>
                </thead>
                <tbody>
                    @foreach ($approveds as $approved)
                        <tr>
                            <td>{{ $approved->name }}</td>
                            <td>{{ $approved->email }}</td>
                            <td>{{ $approved->area_code }}</td>
                            <td>{{ $approved->phone }}</td>
                            <td>{{ $approved->mobile }}</td>
                            <td>{{ $approved->announcement }}</td>
                            <td title="{{ $approved->approvedState->description ?? '' }}">{!! $approved->approvedState->name ?? '&nbsp;' !!}</td>
                            <td>{!! $approved->role->name ?? '&nbsp;' !!}</td>
                            <td>{!! $approved->course->name ?? '&nbsp;' !!}</td>
                            <td>{!! $approved->pole->name ?? '&nbsp;' !!}</td>
                            @if ($approved->approvedState->hasNext())
                                @foreach ($approved->approvedState->getNext() as $state)
                                    <td title="{{ $state->description }}" @if ($approved->approvedState->getNext()->count() == 1) colspan="2" @endif>
                                        <a href="{{ route('approveds.changestate', ['approved' => $approved, 'state' => $state->id]) }}">{{ $state->name }}</a>
                                    </td>
                                @endforeach
                            @else
                                <td colspan="2">
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
