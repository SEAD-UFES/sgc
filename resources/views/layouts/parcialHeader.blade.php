<header>
    <div id="logo"><img src="{{ asset('/sead.png') }}">SGC</div>
    @if ((Auth::check()))
        <nav>
            <ul>
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('auth.logout') }}">Logout</a></li>
            </ul>
        </nav>
    @endif
</header>
