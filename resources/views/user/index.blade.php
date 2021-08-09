@extends('layouts.basic')

@section('title', 'Usuários')

@section('content')
    <section>
        <h2>Usuários</h2>
    </section>
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
                        [ 'label'=>'E-mail', 'value'=>'email_contains', 'selected'=>true],
                        [ 'label'=>'Tipo', 'value'=>'usertype_name_contains'],
                        [ 'label'=>'Ativo', 'value'=>'active_exactly'],
                        [ 'label'=>'Colaborador', 'value'=>'employee_name_contains'],

                    ]
                ]
            )@endcomponent
            <br/>

            <table class="table table-striped table-hover">
                <thead>
                    <th>@sortablelink('email', 'E-mail')</th>
                    <th>@sortablelink('userType.name', 'Tipo')</th>
                    <th>@sortablelink('active', 'Ativo')</th>
                    <th>@sortablelink('employee.name', 'Colaborador')</th>
                    <th colspan="2">Ações</th>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->userType->name }}</td>
                            <td>{{ $user->active === 1 ? 'Sim' : 'Não' }}</td>
                            <td>{{ ($user->employee) ? $user->employee->name : 'Não possui' }}</td>
                            <td><a href="{{ route('users.edit', $user) }}">Editar</a></td>
                            <td>
                                <form name="{{ 'formDelete' . $user->id }}" action="{{ route('users.destroy', $user) }}"
                                    method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <span
                                        onclick="{{ 'if(confirm(\'Tem certeza que deseja excluir esse usuário?\')) document.forms[\'formDelete' . $user->id . '\'].submit();' }}"
                                        style="cursor:pointer; color:blue; text-decoration:underline;">Excluir</span>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {!! $users->links() !!}
            <br />
            <button type="button" onclick="history.back()" class="btn btn-secondary">Voltar</button>
        </main>
    </section>
@endsection

@section('scripts')
@component('_components.filters_script', ['filters' =>$filters] )@endcomponent
@endsection
