<header>
    <div id="logo"><img src="{{ asset('/sead.png') }}">SGC</div>
    @if ((Auth::check()))
        <nav>
            <ul>
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('employee.index') }}">Colaboradores</a></li>
                <li><a href="{{ route('funding') }}">Fomento</a></li>
                <li><a href="{{ route('report') }}">Relatórios</a></li>
                <li><a href="{{ route('system') }}">Sistema</a></li>
                <li><a href="{{ route('auth.logout') }}">Logout</a></li>
            </ul>
        </nav>
    @endif
</header>
