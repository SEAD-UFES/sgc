@extends('layouts.basic')

@section('title', 'Colaboradores')

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
            <li class="breadcrumb-item">{{-- <a href="{{ route('employee') }}"> --}}Colaboradores{{-- </a> --}}</li>
            <li class="breadcrumb-item active" aria-current="page">Listar Colaboradores</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <span style="color: green; font-weight: bold">{{ $message }}</span>
                </div>
            @endif
            @error('noStore')
                <div class="error">> {{ $message }}</div>
            @enderror
            @error('noDestroy')
                <div class="error">> {{ $message }}</div>
            @enderror

            {{-- filtros --}}
            @component(
                '_components.filters_form', 
                [
                    'filters' =>$filters,
                    'options' => [
                        [ 'label'=>'CPF', 'value'=>'cpf_contains', 'selected'=>true],
                        [ 'label'=>'Nome', 'value'=>'name_contains'],
                        [ 'label'=>'Profissão', 'value'=>'job_contains'],
                        [ 'label'=>'Cidade', 'value'=>'addresscity_contains'],
                        [ 'label'=>'Usuário', 'value'=>'user_email_contains'],
                    ]
                ]
            )@endcomponent
            
            <p style="color: red"> Clique no CPF ou Nome para exibir/ocultar as informações de contato</p>
            <table class="table table-striped table-hover">
                <thead>
                    <th>@sortablelink('cpf', 'CPF')</th>
                    <br />
                    <th>@sortablelink('name', 'Nome')</th>
                    <th>@sortablelink('job', 'Profissão')</th>
                    <th>@sortablelink('address_city', 'Cidade')</th>
                    <th>@sortablelink('user.email', 'Usuário')</th>
                    <th colspan="3" class="text-center">Ações</th>
                </thead>
                <tbody>
                    @foreach ($employees as $employee)
                        <tr>
                            <td onclick="toogleById({{ '\'contactLine_' . $employee->id . '\');' }}">
                                {{ $employee->cpf }}
                            </td>
                            <td onclick="toogleById({{ '\'contactLine_' . $employee->id . '\');' }}">
                                {{ $employee->name }}</td>
                            <td>{{ $employee->job }}</td>
                            <td>{{ $employee->address_city }}</td>
                            <td>{{ $employee->user->email ?? '' }}</td>
                            <td class="text-center"><a href="{{ route('employees.show', $employee) }}" class="btn btn-primary btn-sm">Exibir</a></td>
                            <td class="text-center"><a href="{{ route('employees.edit', $employee) }}" class="btn btn-primary btn-sm">Editar</a></td>
                            <td class="text-center">
                                <form name="{{ 'formDelete' . $employee->id }}"
                                    action="{{ route('employees.destroy', $employee) }}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <span
                                        onclick="{{ 'if(confirm(\'Tem certeza que deseja excluir esse Colaborador e todos os seus documentos, vínculos e documentos de vínculos?\')) document.forms[\'formDelete' . $employee->id . '\'].submit();' }}"
                                        {{-- style="cursor:pointer; color:blue; text-decoration:underline;" --}} class="btn btn-danger btn-sm">Excluir</span>
                                </form>
                            </td>
                        </tr>
                        <tr style="display: none" id="contactLine_{{ $employee->id }}">
                            <td colspan="7"><strong>E-mail:</strong> {{ $employee->email }} | <strong>Área:</strong> {{ $employee->area_code }} | <strong>Telefone:</strong> {{ $employee->phone }} | <strong>Celular:</strong> {{ $employee->mobile }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {!! $employees->links() !!}
            <button type="button" onclick="history.back()" class="btn btn-secondary">Voltar</button>
            <br /><br />
        </main>
    </section>
@endsection

@section('scripts')
    @component('_components.filters_script', ['filters' =>$filters] )@endcomponent
@endsection