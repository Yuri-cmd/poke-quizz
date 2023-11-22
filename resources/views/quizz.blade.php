@extends('components/layout')

@section('title', 'PokeQuizz')

@section('content')
<section class="content" id="questions">
    @foreach ($preguntas as $i => $pregunta)
        <div class="pokedex" style="display: {{ $i == 0 ? 'block' : 'none' }}" id="question{{ $i + 1 }}">
            <div class="pokedex-body">
                <div class="pokedex-cabecera">
                    <div class="pokedex-bola">
                        <div class="pokedex-bola-centro"></div>
                    </div>
                    <div class="pokedex-contente-bolitas">
                        <div style="background-color: red;"></div>
                        <div style="background-color: yellow"></div>
                        <div style="background-color: green"></div>
                    </div>
                </div>
                <div class="pokedex-screen">
                    <img src="{{ $pregunta->imagen }}" alt="Pokemon">
                </div>
                <div class="pokedex-buttons">
                    <span>{{ $pregunta->pregunta }}</span>
                    @foreach ($pregunta->respuestas as $key => $respuesta)
                        <a class="pokedex-button answer" data-correct="{{ $respuesta->es_correcta }}">
                            {{ $key + 1 }}. {{ ucfirst($respuesta->respuesta) }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="pokedex-base">
                <button class="next-button" onclick="showNextQuestion()" style="display: none;">Siguiente</button>
                <div class="pokedex-details">
                    <img class="malla" src="/img/malla.jpg" alt="">
                    <img src="/img/pokeball.png" alt="">
                    <img class="malla" src="/img/malla.jpg" alt="">
                </div>
            </div>
        </div>
    @endforeach
</section>
@endsection