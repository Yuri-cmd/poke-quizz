@extends('components/layout')

@section('title', 'PokeQuizz')

@section('content')
    <section class="content-filtros">
        <form action="{{ route('buscar') }}" method="GET" class="search-form">
            <div class="col-auto">
                <input name="q" id="search-input" placeholder="Buscar Pokémon">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn-search">Buscar</button>
            </div>
        </form>
        <form class="search-form" action="{{ route('buscar') }}" method="POST">
            @csrf
            <select id="select-pokemon" name="pokemon">
                <option value="">Elegir</option>
                <option value="Normal">Normal</option>
                <option value="Fire">Fire</option>
                <option value="Water">Water</option>
                <option value="Electric">Electric</option>
                <option value="Grass">Grass</option>
                <option value="Ice">Ice</option>
                <option value="Fighting">Fighting</option>
                <option value="Poison">Poison</option>
                <option value="Ground">Ground</option>
                <option value="Flying">Flying</option>
                <option value="Psychic">Psychic</option>
                <option value="Bug">Bug</option>
                <option value="Rock">Rock</option>
                <option value="Ghost">Ghost</option>
                <option value="Dragon">Dragon</option>
                <option value="Dark">Dark</option>
                <option value="Steel">Steel</option>
                <option value="Fairy">Fairy</option>
            </select>
        </form>
    </section>
    <section class="content-card">
        @foreach ($pokemones as $pokemon)
            <div class="card-pokemon">
                <img class="card-image" src="{{ $pokemon['foto'] ?? 'otro.png' }}" alt="">
                <div class="card-header" style="background-color: {{ $pokemon['colorType'] ?? '' }}">
                    <div class="card-hp">
                        <h3>HP <span>{{ $pokemon['stats']['hp'] }}</span></h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-name">
                        {{ ucwords($pokemon['name']) }}
                    </div>
                    <div class="content-type">
                        @foreach ($pokemon['types'] as $type)
                            <div class="card-type" style="background-color: {{ $type['color'] }}">
                                <span>{{ $type['type'] }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="card-atributes">
                        <div class="atribute">
                            <div class="numero">
                                {{ $pokemon['stats']['attack'] }}
                            </div>
                            <div class="nombre">
                                Attack
                            </div>
                        </div>
                        <div class="atribute">
                            <div class="numero">
                                {{ $pokemon['stats']['defense'] }}
                            </div>
                            <div class="nombre">
                                Defense
                            </div>
                        </div>
                        <div class="atribute">
                            <div class="numero">
                                {{ $pokemon['stats']['speed'] }}
                            </div>
                            <div class="nombre">
                                Speed
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </section>
    @if (isset($currentChunk) && $currentChunk < $totalChunks)
        <div class="content-paginacion">
            @for ($i = 0; $i < $totalChunks; $i++)
                <form action="{{ route('buscar-type') }}" method="get">
                    <button class="btn-paginacion">
                        {{ $i + 1 }}
                        <input type="hidden" name="chunk" value="{{ $i }}">
                        <input type="hidden" name="type" value="{{ $_GET['type'] }}">
                    </button>
                </form>
            @endfor
        </div>

    @endif
    @if (isset($msg) && $msg)
        <section style="width: 100%; display: flex; justify-content: center; align-items: center;">
            <div class="btn-regreso">
                <a href="{{ route('lista-pokemon') }}">
                    Regresar
                </a>
            </div>
        </section>
    @endif
    @if (isset($msg) && !$msg)
        <script>
            Swal.fire({
                title: '¡No se encontro el pokemon buscado!',
                text: 'Intente buscando con otro nombre.',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'ok',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            })
        </script>
    @endif
@endsection
