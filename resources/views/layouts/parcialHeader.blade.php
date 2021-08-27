<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('root') }}"><img src="{{ asset('/sead.png') }}" alt="" width="30"
                class="d-inline-block align-text-top">&nbsp;SGC&nbsp;</a>
        @if (Auth::check())
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="{{ route('home') }}">Home</a></li>

                    @canany(['isAdm', 'isDir', 'isAss', 'isSec', 'isCoord'])
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toogle" href="" {{-- href="{{ route('employee') }}" --}} id="navbarSupportedContentMenuLink1" role="button" data-bs-toggle="dropdown" aria-expanded="false">Colaboradores</a>
                            <ul class="dropdown-menu" aria-labelledby="navbarSupportedContentMenuLink1">
                                <li><h6 class="dropdown-header">Aprovados</h6></li>
                                <li><a class="dropdown-item" href="{{ route('approveds.index') }}">Listar Aprovados</a></li>
                                @can('approved-store')
                                    <li><a class="dropdown-item" href="{{ route('approveds.create') }}">Importar Aprovados</a></li>
                                @endcan
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">Colaboradores</h6></li>
                                <li><a class="dropdown-item" href="{{ route('employees.index') }}">Listar Colaboradores</a></li>
                                <li><a class="dropdown-item" href="{{ route('employees.create') }}">Cadastrar Colaborador</a></li>
                                <li><a class="dropdown-item" href="{{ route('employees.document.index') }}">Listar Documentos de Colaboradores</a></li>
                                <li><a class="dropdown-item" href="{{ route('employees.document.create') }}">Importar Documentos de Colaborador</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">Vínculos</h6></li>
                                <li><a class="dropdown-item" href="{{ route('bonds.index') }}">Listar Vínculos</a></li>
                                <li><a class="dropdown-item" href="{{ route('bonds.create') }}">Cadastrar Vínculo</a></li>
                                <li><a class="dropdown-item" href="{{ route('bonds.document.index') }}">Listar Documento de Vínculos</a></li>
                                <li><a class="dropdown-item" href="{{ route('bonds.document.create') }}">Importar Documento de Vínculo</a></li>
                            </ul>
                        </li>
                        {{-- <li class="nav-item"><a class="nav-link" href="{{ route('funding') }}">Fomento</a></li> --}}
                    @endcanany

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toogle" href="" {{-- href="{{ route('report') }}" --}} id="navbarSupportedContentMenuLink3" role="button" data-bs-toggle="dropdown" aria-expanded="false">Relatórios</a>
                        <ul class="dropdown-menu" aria-labelledby="navbarSupportedContentMenuLink3">
                            <li><h6 class="dropdown-header">Termos e Licença</h6></li>
                            <li><a class="dropdown-item" href="{{ route('bonds.rights.index') }}">Listar Documentos de Termos e Licença</a></li>
                        </ul>
                    </li>

                    @canany(['isAdm', 'isDir', 'isAss', 'isSec', 'isCoord'])
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toogle" href="" {{-- href="{{ route('system') }}" --}} id="navbarSupportedContentMenuLink4" role="button" data-bs-toggle="dropdown" aria-expanded="false">Sistema</a>
                            <ul class="dropdown-menu" aria-labelledby="navbarSupportedContentMenuLink4">
                                <li><h6 class="dropdown-header">Atribuições</h6></li>
                                <li><a class="dropdown-item" href="{{ route('roles.index') }}">Listar Atribuições</a></li>
                                @canany(['isAdm', 'isDir', 'isAss', 'isSec'])
                                <li><a class="dropdown-item" href="{{ route('roles.create') }}">Cadastrar Atribuição</a></li>
                                @endcanany
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">Cursos</h6></li>
                                <li><a class="dropdown-item" href="{{ route('courses.index') }}">Listar Cursos</a></li>
                                @canany(['isAdm', 'isDir', 'isAss', 'isSec'])
                                <li><a class="dropdown-item" href="{{ route('courses.create') }}">Cadastrar Curso</a></li>
                                @endcanany
                                <li><a class="dropdown-item" href="{{ route('coursetypes.index') }}">Listar Tipos de Cursos</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">Polos</h6></li>
                                <li><a class="dropdown-item" href="{{ route('poles.index') }}">Listar Polos</a></li>
                                @canany(['isAdm', 'isDir', 'isAss', 'isSec'])
                                <li><a class="dropdown-item" href="{{ route('poles.create') }}">Cadastrar Polo</a></li>
                                @endcanany
                                @canany(['isAdm'])
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">Usuários</h6></li>
                                <li><a class="dropdown-item" href="{{ route('users.index') }}">Listar Usuários</a></li>
                                <li><a class="dropdown-item" href="{{ route('users.create') }}">Cadastrar Usuário</a></li>
                                @endcanany
                                @canany(['isAdm'])
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">Atribuições de papel</h6></li>
                                <li><a class="dropdown-item" href="{{ route('userTypeAssignments.index') }}">Listar Atribuições de Papel</a></li>
                                <li><a class="dropdown-item" href="{{ route('userTypeAssignments.create') }}">Cadastrar Atrib. de Papel</a></li>
                                @endcanany
                            </ul>
                        </li>
                    @endcanany
                </ul>

                @if (session('sessionUser')->hasUTAs())
                    <form class="d-flex" action={{ route('currentUTA.change') }} method="POST">
                        @csrf
                        <select class="form-select form-select-sm" aria-label="uta" name="activeUTAs" data-bs-toggle="tooltip" data-bs-placement="left" title="Mudar papel atual" onchange="submit();">
                            @foreach (session('sessionUser')->getActiveUTAs() as $uta)
                                <option value="{{ $uta->id }}"
                                    {{ session('sessionUser')->getCurrentUTA() && $uta->id === session('sessionUser')->getCurrentUTA()->id ? 'selected' : '' }}>
                                    {{ $uta->userType->name }}{{ $uta->course ? " - ".$uta->course->name : '' }}</option>
                            @endforeach
                        </select>
                    </form>
                @endif

                <ul class="list-unstyled ms-1 mb-2 mb-lg-0">
                    <li>Bem vind{{ session('sessionUser')->genderArticle }}, {{ session('sessionUser')->name }}!
                        &nbsp;<a class="btn btn-sm btn-danger" href="{{ route('auth.logout') }}" data-bs-toggle="tooltip" data-bs-placement="left" title="Sair do sistema">Sair</a>
                    </li>
                </ul>
            </div>
        @endif
    </div>
</nav>