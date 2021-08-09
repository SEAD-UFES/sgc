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
            <li class="breadcrumb-item"><a href="{{ route('employee') }}">Colaboradores</a></li>
            <li class="breadcrumb-item active" aria-current="page">Listar Colaboradores</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p style="color: green; font-weight: bold">{{ $message }}</p>
                </div><br />
            @endif

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
            <br/>            
            

            @error('noStore')
                <div class="error">> {{ $message }}</div>
            @enderror
            @error('noDestroy')
                <div class="error">> {{ $message }}</div>
            @enderror
            <p style="color: red"> Clique no CPF ou Nome para exibir/ocultar as informações de contato</p>
            <table class="table table-striped table-hover">
                <thead>
                    <th>@sortablelink('cpf', 'CPF')</th>
                    <br />
                    <th>@sortablelink('name', 'Nome')</th>
                    <th>@sortablelink('job', 'Profissão')</th>
                    {{-- <th>Gênero</th>
                    <th>Nascimento</th>
                    <th>UF Nasc.</th>
                    <th>Cidade Nasc.</th>
                    <th>Num. Documento</th>
                    <th>Tipo Documento</th>
                    <th>Expedição</th>
                    <th>Expedidor</th>
                    <th>E. Civil</th>
                    <th>Cônjuge</th>
                    <th>Pai</th>
                    <th>Mãe</th>
                    <th>Logradouro</th>
                    <th>Complemento</th>
                    <th>Número</th>
                    <th>Bairro</th>
                    <th>CEP</th>
                    <th>UF</th> --}}
                    <th>@sortablelink('address_city', 'Cidade')</th>
                    {{-- <th>Área</th>
                    <th>Telefone</th>
                    <th>Celular</th>
                    <th>E-mail</th> --}}
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
                            {{-- <td>{{ $employee->gender->name }}</td>
                            <td>{{ $employee->birthday }}</td>
                            <td>{{ $employee->birthState->uf }}</td>
                            <td>{{ $employee->birth_city }}</td>
                            <td>{{ $employee->id_number }}</td>
                            <td>{{ $employee->documentType->name }}</td>
                            <td>{{ $employee->id_issue_date }}</td>
                            <td>{{ $employee->id_issue_agency }}</td>
                            <td>{{ $employee->maritalStatus->name }}</td>
                            <td>{{ $employee->spouse_name }}</td>
                            <td>{{ $employee->father_name }}</td>
                            <td>{{ $employee->mother_name }}</td>
                            <td>{{ $employee->address_street }}</td>
                            <td>{{ $employee->address_complement }}</td>
                            <td>{{ $employee->address_number }}</td>
                            <td>{{ $employee->address_district }}</td>
                            <td>{{ $employee->address_postal_code }}</td>
                            <td>{{ $employee->addressState->uf }}</td> --}}
                            <td>{{ $employee->address_city }}</td>
                            {{-- <td>{{ $employee->area_code }}</td>
                            <td>{{ $employee->phone }}</td>
                            <td>{{ $employee->mobile }}</td>
                            <td>{{ $employee->email }}</td> --}}
                            <td class="text-center"><a href="{{ route('employees.show', $employee) }}">Exibir</a></td>
                            <td class="text-center"><a href="{{ route('employees.edit', $employee) }}">Editar</a></td>
                            <td class="text-center">
                            <td>{{ $employee->user->email ?? '' }}</td>
                                <form name="{{ 'formDelete' . $employee->id }}"
                                    action="{{ route('employees.destroy', $employee) }}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <span
                                        onclick="{{ 'if(confirm(\'Tem certeza que deseja excluir esse Colaborador e todos os seus documentos, vínculos e documentos de vínculos?\')) document.forms[\'formDelete' . $employee->id . '\'].submit();' }}"
                                        style="cursor:pointer; color:blue; text-decoration:underline;">Excluir</span>
                                </form>
                            </td>
                        </tr>
                        <tr style="background-color: lightgrey; display: none" id="contactLine_{{ $employee->id }}">
                            <td style="font-weight: bold">E-mail:</td>
                            <td>{{ $employee->email }}</td>
                            <td style="font-weight: bold">Área:</td>
                            <td>{{ $employee->area_code }}</td>
                            <td style="font-weight: bold">Telefone:</td>
                            <td>{{ $employee->phone }}</td>
                            <td style="font-weight: bold">Celular:</td>
                            <td>{{ $employee->mobile }}</td>
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