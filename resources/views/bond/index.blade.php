@extends('layouts.basic')

@section('title', 'Vínculos')

@section('content')
    <script>
        function toogleById(id) {
            if (document.getElementById(id).style.display == 'table-row')
                document.getElementById(id).style.display = 'none';
            else
                document.getElementById(id).style.display = 'table-row';
        }
    </script>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item"><a href="{{ route('employee') }}">Colaboradores</a></li>
            <li class="breadcrumb-item active" aria-current="page">Listar Vínculos</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p style="color: green; font-weight: bold">{{ $message }}</p>
                </div><br />
            @endif
            <p style="color: red"> Clique no Nome ou Atribuição para exibir/ocultar as informações de datas</p>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>@sortablelink('employee.name', 'Colaborador')</th>
                        <th>@sortablelink('role.name', 'Atribuição')</th>
                        <th>@sortablelink('course.name', 'Curso')</th>
                        <th>@sortablelink('pole.name', 'Polo')</th>
                        {{-- <th>Início</th>
                        <th>Fim</th>
                        <th>Encerrado em</th> --}}
                        <th>@sortablelink('volunteer', 'Voluntário') </th>
                        {{-- <th>Verificado em</th> --}}
                        <th>@sortablelink('impediment', 'Impedido') </th>
                        <th colspan="4" class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bonds as $bond)
                        <tr>
                            <td onclick="toogleById({{ '\'datesLine_' . $bond->id . '\');' }}">
                                {{ $bond->employee->name }}</td>
                            <td onclick="toogleById({{ '\'datesLine_' . $bond->id . '\');' }}">
                                {{ $bond->role->name }}
                            </td>
                            <td>{{ $bond->course->name }}</td>
                            <td>{{ $bond->pole->name }}</td>
                            {{-- <td>{{ \Carbon\Carbon::parse($bond->begin)->isoFormat('DD/MM/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($bond->end)->isoFormat('DD/MM/Y') }}</td>
                            <td>{{ isset($bond->terminated_on) ? \Carbon\Carbon::parse($bond->terminated_on)->isoFormat('DD/MM/Y') : '' }}
                            </td> --}}
                            <td>{{ $bond->volunteer === 1 ? 'Sim' : 'Não' }}</td>
                            {{-- <td>{{ isset($bond->uaba_checked_on) ? \Carbon\Carbon::parse($bond->uaba_checked_on)->isoFormat('DD/MM/Y') : '' }} --}}
                            </td>
                            <td>{{ $bond->impediment === 1 ? 'Sim' : 'Não' }}</td>
                            <td class="text-center"><a href="{{ route('bonds.show', $bond) }}">Detalhes</a></td>
                            <td class="text-center"><a href="{{ route('employees.show', $bond->employee) }}">Ver Colaborador</a></td>
                            <td class="text-center"><a href="{{ route('bonds.edit', $bond->id) }}">Editar vínculo</a></td>
                            <td class="text-center">
                                <form name="{{ 'formDelete' . $bond->id }}"
                                    action="{{ route('bonds.destroy', $bond) }}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <span
                                        onclick="{{ 'if(confirm(\'Tem certeza que deseja excluir esse Vínculo?\')) document.forms[\'formDelete' . $bond->id . '\'].submit();' }}"
                                        style="cursor:pointer; color:blue; text-decoration:underline;">Excluir</span>
                                </form>
                            </td>
                        </tr>
                        <tr style="display: none" id="datesLine_{{ $bond->id }}">
                            <th>Início</th>
                            <td>{{ \Carbon\Carbon::parse($bond->begin)->isoFormat('DD/MM/Y') }}</td>
                            <th>Fim</th>
                            <td>{{ \Carbon\Carbon::parse($bond->end)->isoFormat('DD/MM/Y') }}</td>
                            <th>Encerrado</th>
                            <td>{{ isset($bond->terminated_on) ? \Carbon\Carbon::parse($bond->terminated_on)->isoFormat('DD/MM/Y') : '' }}
                            </td>
                            <th>Verificado</th>
                            <td colspan="2">
                                {{ isset($bond->uaba_checked_on) ? \Carbon\Carbon::parse($bond->uaba_checked_on)->isoFormat('DD/MM/Y') : '' }}
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {!! $bonds->links() !!}
            <button type="button" onclick="history.back()" class="btn btn-secondary">Voltar</button>
            <br /><br />
        </main>
    </section>
@endsection
