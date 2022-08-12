@extends('layouts.basic')

@section('title', 'Não autorizado')

@section('content')
    <script>
        function startTimer(duration, display) {
            var timer = duration;

            setInterval(function() {
                var seconds = timer % 60;
                var minutes = (timer - seconds) / 60;
                if (seconds.toString().length == 1) {
                    seconds = "0" + seconds;
                }
                if (minutes.toString().length == 1) {
                    minutes = "0" + minutes;
                }
                document.getElementById("time").innerHTML = " " + seconds;
                timer--;
                if (timer == 0) {
                    window.location.href = "{{ route('auth.form') }}";
                }
            }, 1000);
        }

        window.onload = function() {
            var seconds = 10,
                display = document.getElementById('time');
            startTimer(seconds, display);
        };
    </script>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Sistema</li>
            <li class="breadcrumb-item active" aria-current="page">Não autorizado</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-xxl-8">
                    <h4>Não autorizado [Código 401]</h4>
                    <p>Credenciais de usuário inválidas.</p>
                    <p>Clique <a href="{{ route('auth.form') }}"><strong>aqui</strong></a> para ir para o formulário de login. Ou aguarde<span id="time"></span> segundos...</p>
                </div>
            </div>
        </main>
    </section>
@endsection