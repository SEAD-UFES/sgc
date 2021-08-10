<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><img src="{{ asset('/sead.png') }}" alt="" width="30"
                class="d-inline-block align-text-top">&nbsp;SGC&nbsp;</a>
        @if (Auth::check())
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" aria-current="page"
                            href="{{ route('home') }}">Home</a></li>

                    @canany(['isAdm', 'isDir', 'isAss', 'isSec', 'isCor'])
                        <li class="nav-item"><a class="nav-link" href="{{ route('employee') }}">Colaboradores</a></li>
                        {{-- <li class="nav-item"><a class="nav-link" href="{{ route('funding') }}">Fomento</a></li> --}}
                    @endcanany

                    <li class="nav-item"><a class="nav-link" href="{{ route('report') }}">Relatórios</a></li>

                    @canany(['isAdm', 'isDir', 'isAss', 'isSec', 'isCor'])
                        <li class="nav-item"><a class="nav-link" href="{{ route('system') }}">Sistema</a></li>
                    @endcanany
                </ul>

                @if (session('sessionUser')->hasBond)
                    <form class="d-flex" action={{ route('currentBond.change') }} method="POST">
                        @csrf
                        <select class="form-select" aria-label="Vínculo" name="activeBonds" onchange="submit();">
                            @foreach (session('sessionUser')->bonds as $bond)
                                <option value="{{ $bond->id }}"
                                    {{ $bond->id === session('sessionUser')->currentBond->id ? 'selected' : '' }}>
                                    {{ $bond->role->name }} - {{ $bond->course->name }}</option>
                            @endforeach
                        </select>
                    </form>
                @endif &nbsp;

                <ul class="list-unstyled ms-1 mb-2 mb-lg-0">
                    <li>Bem vind{{ session('sessionUser')->genderArticle }}, {{ session('sessionUser')->name }}!
                        [<a href="{{ route('auth.logout') }}">Sair</a>]</li>

                </ul>
            </div>
        @endif
    </div>
</nav>
