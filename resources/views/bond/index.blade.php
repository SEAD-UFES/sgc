@extends('layouts.basic')

@section('title', 'Listar Vínculos')

@section('content')
    <section>
        <strong>Listar Vínculos</strong>
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
                    <tr>
                    <th colspan="4">SEAD</th>
                    <th colspan="4">Vínculo</th>
                    <th colspan="2">UAB</th>
                    <th rowspan="2" colspan="3">Ações</th>
                    </tr>
                    <tr>
                    <th>Colaborador</th>
                    <th>Atribuição</th>
                    <th>Curso</th>
                    <th>Polo</th>
                    <th>Início</th>
                    <th>Fim</th>
                    <th>Encerrado em</th>
                    <th>Voluntário</th>
                    <th>Verificado em</th>
                    <th>Impedido</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bonds as $bond)
                        <tr>
                            <td>{{ $bond->employee->name }}</td>
                            <td>{{ $bond->role->name }}</td>
                            <td>{{ $bond->course->name }}</td>
                            <td>{{ $bond->pole->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($bond->begin)->isoFormat('DD/MM/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($bond->end)->isoFormat('DD/MM/Y') }}</td>
                            <td>{{ isset($bond->terminated_on) ? \Carbon\Carbon::parse($bond->terminated_on)->isoFormat('DD/MM/Y') : '' }}
                            </td>
                            <td>{{ $bond->volunteer === 1 ? 'Sim' : 'Não' }}</td>
                            <td>{{ isset($bond->uaba_checked_on) ? \Carbon\Carbon::parse($bond->uaba_checked_on)->isoFormat('DD/MM/Y') : '' }}
                            </td>
                            <td>{{ $bond->impediment === 1 ? 'Sim' : 'Não' }}</td>
                            <td><a href="{{ route('employees.show', $bond->employee) }}">Ver Colaborador</a></td>
                            <td><a href="{{ route('bonds.edit', $bond->id) }}">Editar vínculo</a></td>
                            <td>
                                <form name="{{ 'formDelete' . $bond->id }}" action="{{ route('bonds.destroy', $bond) }}"
                                    method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <span
                                        onclick="{{ 'if(confirm(\'Tem certeza que deseja excluir esse Vínculo?\')) document.forms[\'formDelete' . $bond->id . '\'].submit();' }}"
                                        style="cursor:pointer; color:blue; text-decoration:underline;">Excluir</span>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {!! $bonds->links() !!}
            <br />
        </main>
    </section>
@endsection
