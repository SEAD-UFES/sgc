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
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="{{ route('home') }}">Início</a></li>

                    @cannot('isLdi') {{-- @canany(['isAdm', 'isDir', 'isAss', 'isSec', 'isCoord']) --}}
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toogle" href="" id="navbarSupportedContentMenuLink1" role="button" data-bs-toggle="dropdown" aria-expanded="false">Colaboradores</a>
                            <ul class="dropdown-menu" aria-labelledby="navbarSupportedContentMenuLink1">
                                @canany(['applicant-list', 'applicant-store'])
                                    <li><h6 class="dropdown-header">Aprovados</h6></li>
                                @endcanany
                                @can('applicant-list')
                                    <li><a class="dropdown-item" href="{{ route('applicants.index') }}">Listar{{--  Aprovados --}}</a></li>
                                @endcan
                                @can('applicant-store')
                                    <li><a class="dropdown-item" href="{{ route('applicants.create') }}">Cadastrar{{--  Aprovado --}}</a></li>
                                    <li><a class="dropdown-item" href="{{ route('applicants.create_many.step_1') }}">Importar Planilha</a></li>
                                @endcan
                                @canany(['applicant-list', 'applicant-store'])
                                    <li><hr class="dropdown-divider"></li>
                                @endcanany
                                <li><h6 class="dropdown-header">Colaboradores</h6></li>
                                @can('employee-list')
                                    <li><a class="dropdown-item" href="{{ route('employees.index') }}">Listar{{--  Colaboradores --}}</a></li>
                                @endcan
                                @can('employee-store')
                                    <li><a class="dropdown-item" href="{{ route('employees.create') }}">Cadastrar{{--  Colaborador --}}</a></li>
                                @endcan
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">Vínculos</h6></li>
                                @can('bond-list')
                                    <li><a class="dropdown-item" href="{{ route('bonds.index') }}">Listar{{--  Vínculos --}}</a></li>
                                @endcan
                                @can('bond-create')
                                    <li><a class="dropdown-item" href="{{ route('bonds.create') }}">Cadastrar{{--  Vínculo --}}</a></li>
                                @endcan
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">Documentos de Vínculos</h6></li>
                                @can('bondDocument-list')
                                    <li><a class="dropdown-item" href="{{ route('documents.index') }}">Listar{{--  Documentos --}}</a></li>
                                @endcan
                                @can('bondDocument-store')
                                    <li><a class="dropdown-item" href="{{ route('documents.create') }}">Importar{{--  Documento --}}</a></li>
                                @endcan
                            </ul>
                        </li>
                        {{-- <li class="nav-item"><a class="nav-link" href="{{ route('funding') }}">Fomento</a></li> --}}
                    @endcannot

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toogle" href="" id="navbarSupportedContentMenuLink3" role="button" data-bs-toggle="dropdown" aria-expanded="false">Relatórios</a>
                        <ul class="dropdown-menu" aria-labelledby="navbarSupportedContentMenuLink3">
                            <li><h6 class="dropdown-header">Termos de cessão de direitos</h6></li>
                            <li><a class="dropdown-item" href="{{ route('rights.index') }}">Listar{{--  Documentos de Termos e Licença --}}</a></li>
                        </ul>
                    </li>

                    @cannot('isLdi') {{-- @canany(['isAdm', 'isDir', 'isAss', 'isSec', 'isCoord']) --}}
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toogle" href="" id="navbarSupportedContentMenuLink4" role="button" data-bs-toggle="dropdown" aria-expanded="false">Sistema</a>
                            <ul class="dropdown-menu" aria-labelledby="navbarSupportedContentMenuLink4">
                                <li><h6 class="dropdown-header">Funções</h6></li>
                                <li><a class="dropdown-item" href="{{ route('roles.index') }}">Listar{{--  Funções --}}</a></li>
                                @can('role-store')
                                    <li><a class="dropdown-item" href="{{ route('roles.create') }}">Cadastrar{{--  Função --}}</a></li>
                                @endcan
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">Cursos</h6></li>
                                <li><a class="dropdown-item" href="{{ route('courses.index') }}">Listar{{--  Cursos --}}</a></li>
                                @can('course-store')
                                    <li><a class="dropdown-item" href="{{ route('courses.create') }}">Cadastrar{{--  Curso --}}</a></li>
                                @endcan
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">Polos</h6></li>
                                <li><a class="dropdown-item" href="{{ route('poles.index') }}">Listar{{--  Polos --}}</a></li>
                                @can('pole-store')
                                    <li><a class="dropdown-item" href="{{ route('poles.create') }}">Cadastrar{{--  Polo --}}</a></li>
                                @endcan
                                @can('isAdm-global')
                                    <li><hr class="dropdown-divider"></li>
                                    <li><h6 class="dropdown-header">Usuários</h6></li>
                                    <li><a class="dropdown-item" href="{{ route('users.index') }}">Listar{{--  Usuários --}}</a></li>
                                    <li><a class="dropdown-item" href="{{ route('users.create') }}">Cadastrar{{--  Usuário --}}</a></li>
                                @endcan
                                @can('isAdm-global')
                                    <li><hr class="dropdown-divider"></li>
                                    <li><h6 class="dropdown-header">Atribuições de papel</h6></li>
                                    <li><a class="dropdown-item" href="{{ route('responsibilities.index') }}">Listar{{--  Atribuições de Papel --}}</a></li>
                                    <li><a class="dropdown-item" href="{{ route('responsibilities.create') }}">Cadastrar{{--  Atrib. de Papel --}}</a></li>
                                @endcan
                                @can('isAdm-global')
                                    <li><hr class="dropdown-divider"></li>
                                    <li><h6 class="dropdown-header">SGC</h6></li>
                                    <li><a class="dropdown-item" href="{{ route('system_logs.index') }}">Logs de sistema</a></li>
                                    <li><a class="dropdown-item" href="{{ route('system_info') }}">System Info</a></li>
                                @endcan
                            </ul>
                        </li>
                    @endcannot
                </ul>
                @if ($activeResponsibilities != null)
                <form class="d-flex" action={{ route('users.responsibility_switch') }} method="POST">
                    @csrf
                    <select class="form-select form-select-sm" aria-label="responsibility" name="responsibility_id" data-bs-toggle="tooltip" data-bs-placement="left" title="Mudar papel atual" onchange="submit();">
                            @foreach ($activeResponsibilities as $responsibility)
                                <option value="{{ $responsibility->getAttribute('id') }}"
                                    {{ $responsibility->getAttribute('id') === $currentResponsibility?->getAttribute('id') ? 'selected' : '' }}>
                                    {{ $responsibility->userType->name }}{{ $responsibility->course ? ' - ' . $responsibility->course->name : '' }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                @endif
                <ul class="list-unstyled ms-1 mb-2 mb-lg-0">
                    <li>Bem vind{{ $gender === 'F' ? 'a' : 'o'}}, <a href="{{ route('users.current_password_edit') }}" data-bs-toggle="tooltip" data-bs-placement="left" title="Alterar senha do Usuário">{{ $name }}</a>!
                        &nbsp;<a class="btn btn-sm btn-danger" href="{{ route('auth.logout') }}" data-bs-toggle="tooltip" data-bs-placement="left" title="Sair do sistema">Sair</a>
                    </li>
                </ul>
            </div>
        @endif
    </div>
</nav>
