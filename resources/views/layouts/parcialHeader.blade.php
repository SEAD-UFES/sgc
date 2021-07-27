<header>
    <div>
        <div id="logo"><img src="{{ asset('/sead.png') }}">SGC</div>
        @if (Auth::check())
            <div style="float: right;">
                <div style="text-align:right;">Bem vind{{ session('sessionUser')->genderArticle }},
                    {{ session('sessionUser')->name }}! (<a href="{{ route('auth.logout') }}">Sair</a>)</div>
                @if (session('sessionUser')->hasBond)
                    <form action={{ route('currentBond.change') }} method="POST">
                        @csrf
                        <div style="margin-top: 3px">Vínculo:
                            <select name="activeBonds" onchange="submit();">
                                @foreach (session('sessionUser')->bonds as $bond)
                                    <option value="{{ $bond->id }}"
                                        {{ $bond->id === session('sessionUser')->currentBond->id ? 'selected' : '' }}>
                                        {{ $bond->role->name }} - {{ $bond->course->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                @endif
            </div>
        @endif
    </div>
    <br />
    @if (Auth::check())
        <nav>
            <ul>
                <li><a href="{{ route('home') }}">Home</a></li>
                @canany(['isAdm', 'isDir', 'isAss', 'isSec', 'isCor'])
                    <li><a href="{{ route('employee') }}">Colaboradores</a></li>
                    <li><a href="{{ route('funding') }}">Fomento</a></li>
                @endcanany
                <li><a href="{{ route('report') }}">Relatórios</a></li>
                @canany(['isAdm', 'isDir', 'isAss', 'isSec', 'isCor'])
                    <li><a href="{{ route('system') }}">Sistema</a></li>
                @endcanany
                {{-- <li><a href="{{ route('auth.logout') }}">Logout</a></li> --}}
            </ul>
        </nav>
    @endif
</header>
