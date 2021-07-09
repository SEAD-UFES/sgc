@extends('layouts.basic')

@section('title', 'Home')

@section('content')
    <section>
        <strong>Home</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <p>Bem vindo{{(Auth::check()) ? ', ' . (Auth::user()->email) : '' }}! (<a href="{{ route('auth.logout') }}">Sair</a>)</p>
        </main>
    </section>
@endsection
