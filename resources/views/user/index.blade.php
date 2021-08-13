@extends('layouts.basic')

@section('title', 'Usuários')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">{{-- <a href="{{ route('system') }}"> --}}Sistema{{-- </a> --}}</li>
            <li class="breadcrumb-item active" aria-current="page">Listar Usuários</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <span style="color: green; font-weight: bold">{{ $message }}</span>
                </div>
            @endif

            {{-- filtros --}}
            @component(
                '_components.filters_form', 
                [
                    'filters' => $filters,
                    'options' => [
                        ['label' => 'E-mail', 'value' => 'email_contains', 'selected' => true], 
                        ['label' => 'Tipo', 'value' => 'usertype_name_contains'], 
                        ['label' => 'Ativo', 'value' => 'active_exactly'], 
                        ['label' => 'Colaborador', 'value' => 'employee_name_contains']
                    ],
                ]
            )@endcomponent

            <table class="table table-striped table-hover">
                <thead>
                    <th>@sortablelink('email', 'E-mail')</th>
                    <th>@sortablelink('userType.name', 'Tipo')</th>
                    <th>@sortablelink('active', 'Ativo')</th>
                    <th>@sortablelink('employee.name', 'Colaborador')</th>
                    <th colspan="2" class="text-center">Ações</th>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->userType->name }}</td>
                            <td>{{ $user->active === 1 ? 'Sim' : 'Não' }}</td>
                            <td>{{ $user->employee ? $user->employee->name : 'Não possui' }}</td>
                            <td class="text-center"><a href="{{ route('users.edit', $user) }}" class="btn btn-primary btn-sm">Editar</a></td>
                            <td class="text-center">
                                <form name="{{ 'formDelete' . $user->id }}" action="{{ route('users.destroy', $user) }}"
                                    method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <span
                                        onclick="{{ 'if(confirm(\'Tem certeza que deseja excluir esse usuário?\')) document.forms[\'formDelete' . $user->id . '\'].submit();' }}"
                                        {{-- style="cursor:pointer; color:blue; text-decoration:underline;" --}} class="btn btn-danger btn-sm">Excluir</span>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {!! $users->links() !!}
            <button type="button" onclick="history.back()" class="btn btn-secondary">Voltar</button>
            <br /><br />
        </main>
    </section>
@endsection

@section('scripts')
    @component('_components.filters_script', ['filters' => $filters])@endcomponent
@endsection
