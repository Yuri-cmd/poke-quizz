<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="/img/pokeball.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/tsparticles-confetti@2.11.0/tsparticles.confetti.bundle.min.js"></script>
    <title>@yield('title')</title>
</head>

<body>
    <header>
        <nav>
            <div>
                <img src="/img/pokeball.png" alt="pokeball icon">
                <h1>POKE QUIZZ</h1>
            </div>

            <div class="datos">
                <div>
                    <a href="{{ route('lista-pokemon') }}">
                        Ver Pokemon
                    </a>
                </div>
                <div class="session-datos">
                    @if (session('nombre') !== null)
                        <div>
                            <p>
                                Hola, {{ session('nombre') }}
                            </p>
                        </div>
                        <div>
                            @if (session('genero') == 'masculino')
                                <img src="/img/ash.png" alt="">
                            @elseif (session('genero') == 'femenino')
                                <img src="/img/may.png" alt="">
                            @else
                                <img src="/img/otro.png" alt="">
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </nav>
    </header>
    <main>
        @yield('content')
    </main>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="/js/index.js"></script>
</body>

</html>
