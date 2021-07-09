@extends('layouts.basic')

@section('title', 'Listar Colaboradores')

@section('content')
    <section>
        <strong>Listar Colaboradores</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p style="color: green; font-weight: bold">{{ $message }}</p>
                </div><br />
            @endif
            {{-- @dd($employees) --}}
            <table>
                <thead>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Profissão</th>
                    <th>Gênero</th>
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
                    <th>UF</th>
                    <th>Cidade</th>
                    <th>Área</th>
                    <th>Telefone</th>
                    <th>Celular</th>
                    <th>E-mail</th>
                    <th>Usuário</th>
                    <th colspan="3">Ações</th>
                </thead>
                <tbody>
                    @foreach ($employees as $employee)
                        <tr>
                            <td>{{ $employee->name }}</td>
                            <td>{{ $employee->cpf }}</td>
                            <td>{{ $employee->job }}</td>
                            <td>{{ $employee->gender->name }}</td>
                            <td>{{ $employee->birthday }}</td>
                            <td>{{ $employee->birthState->uf }}</td>
                            <td>{{ $employee->birth_city }}</td>
                            <td>{{ $employee->id_number }}</td>
                            <td>{{ $employee->idType->name }}</td>
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
                            <td>{{ $employee->addressState->uf }}</td>
                            <td>{{ $employee->address_city }}</td>
                            <td>{{ $employee->area_code }}</td>
                            <td>{{ $employee->phone }}</td>
                            <td>{{ $employee->mobile }}</td>
                            <td>{{ $employee->email }}</td>
                            <td>{{ $employee->user_id }}</td>
                            <td><a href="{{ route('employees.show', $employee) }}">Exibir</a></td>
                            <td><a href="{{ route('employees.edit', $employee) }}">Editar</a></td>
                            <td>
                                <form name="{{ 'formDelete' . $employee->id }}" action="{{ route('employees.destroy', $employee) }}"
                                    method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <span
                                        onclick="{{ 'if(confirm(\'Tem certeza que deseja excluir esse Colaborador?\')) document.forms[\'formDelete' . $employee->id . '\'].submit();' }}"
                                        style="cursor:pointer; color:blue; text-decoration:underline;">Excluir</span>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {!! $employees->links() !!}
            <br />
        </main>
    </section>
@endsection
