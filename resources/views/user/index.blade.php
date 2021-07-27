@extends('layouts.basic')

@section('title', 'Listar Usuários')

@section('content')
    <section>
        <strong>Listar Usuários</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p style="color: green; font-weight: bold">{{ $message }}</p>
                </div><br />
            @endif

            <div style="border: 1px solid black; padding:1px; display: flex; flex-wrap: wrap; align-items: center; ">

                <div style="flex-grow: 1;">
                    <span>Filtros: </span>
                    @if($filters['email'])
                        <a href="#" onclick="removeFilter('email')">(Email: {{ $filters['email'] }})</a>
                    @endif

                    @if($filters['user_type'])
                        <a href="#" onclick="removeFilter('user_type')">(Tipo: {{ $filters['user_type'] }})</a>
                    @endif

                    @if($filters['active'])
                        <a href="#" onclick="removeFilter('active')">(Ativo: {{ $filters['active'] }})</a>
                    @endif

                    @if($filters['employee'])
                        <a href="#" onclick="removeFilter('employee')">(Colaborador: {{ $filters['employee'] }})</a>
                    @endif
                </div>

                <div style="flex-grow: 1; display: flex; justify-content: flex-end;">
                    <form onsubmit="submitFilters(event)">
                        <select id="filterType" name='type'>
                            <option value='email' selected>E-mail</option>
                            <option value='user_type'>Tipo</option>
                            <option value='active'>Ativo</option>
                            <option value='employee'>Colaborador</option>
                        </select>
                        <input id="filterValue" type="text" name='value' />
                        <button type="submit">Filtrar</button>
                    </form>
                </div>

            </div>
            <br/>

            <table>
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
        </main>
    </section>
@endsection

@section('scripts')
<script type="text/javascript">

    function buildFilters(){
        let filters = [];
        @foreach($filters as $key=>$filter)
            filters.push({ key:'{{$key}}', value:'{{$filter}}' });
        @endforeach
        return filters;
    }

    function buildURL(filters){
        //removing nulls
        filters = filters.filter(filter => filter['value']);

        //building url
        let address = window.location.origin+window.location.pathname+'?';
        filters.map(filter=>{
            address = address+'&'+filter['key']+'='+filter['value'];
        });

        return address;
    }

    function submitFilters(event){
        event.preventDefault();

        //building filters object (on javascript)
        let filters = buildFilters()

        //add new filter
        let type = $("#filterType option:checked").val();
        let new_value = $('#filterValue').val();
        filters.map(filter => {
            if(filter['key'] === type) filter['value'] = new_value 
        });

        //build url
        let address = buildURL(filters);

        //redirect do page
        window.location.href = address;
    }


    function removeFilter(filter_key){
        //building filters object (on javascript)
        let filters = buildFilters()

        //remove filter
        filters.map(filter => {
            if(filter['key'] === filter_key) filter['value'] = null 
        });

        //build url
        let address = buildURL(filters);

        //redirect do page
        window.location.href = address;
    }

</script>
@endsection
