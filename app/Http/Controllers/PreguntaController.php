<?php

namespace App\Http\Controllers;

use App\Models\Pregunta;
use App\Models\Respuesta;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class PreguntaController extends Controller
{
    public function quizz()
    {
        // Obtener el número total de preguntas disponibles en la base de datos
        $numeroTotalPreguntas = Pregunta::count();

        // Generar un conjunto de índices aleatorios sin repetición para seleccionar preguntas únicas
        $indicesAleatorios = range(1, $numeroTotalPreguntas);
        shuffle($indicesAleatorios);
        $indicesAleatorios = array_slice($indicesAleatorios, 0, 10); // Obtener solo 10 preguntas

        // Obtener las preguntas correspondientes a los índices aleatorios
        $preguntas = Pregunta::whereIn('pregunta_id', $indicesAleatorios)->get();

        // Obtener todos los IDs de preguntas seleccionadas
        $idsPreguntas = $preguntas->pluck('pregunta_id')->toArray();

        // Obtener las respuestas para las preguntas seleccionadas
        $respuestas = Respuesta::whereIn('pregunta_id', $idsPreguntas)->get();

        // Asociar las respuestas a las preguntas
        foreach ($preguntas as $pregunta) {
            $pregunta->respuestas = $respuestas->where('pregunta_id', $pregunta->pregunta_id);
        }

        return view('quizz')->with('preguntas', $preguntas);
    }

    public function generarPreguntas()
    {
        // Obtener la lista de todos los Pokémon de la PokeAPI
        $response = Http::get('https://pokeapi.co/api/v2/pokemon/');
        $pokemonList = $response->json()['results'];

        // Obtener los nombres de todos los Pokémon
        $nombresPokemon = array_column($pokemonList, 'name');

        // Iterar sobre cada Pokémon en la lista
        foreach ($pokemonList as $key => $pokemon) {
            // Obtener los detalles del Pokémon de la PokeAPI
            $pokemonResponse = Http::get($pokemon['url']);
            $pokemonDetails = $pokemonResponse->json();

            if (($key % 2) == 0) {
                [$pregunta, $opcionesRespuesta] = $this->pregunta($pokemonDetails, $nombresPokemon, 1);
            } else {
                [$pregunta, $opcionesRespuesta] = $this->pregunta($pokemonDetails, $nombresPokemon, 2);
            }

            // Generar más preguntas sobre habilidades, estadísticas, etc., según tus necesidades.
            // $habilidades = implode(", ", array_column($pokemonDetails['abilities'], 'ability', 'name'));
            // $preguntaHabilidades = [
            //     'pregunta' => "¿Cuáles de estas habilidades tiene este Pokémon? $habilidades",
            //     'imagen' => $pokemonDetails['sprites']['front_default'],
            //     'respuesta_correcta' => $pokemonDetails['abilities'][0]['ability']['name'],
            // ];
            // ray(array_column($pokemonDetails['abilities'], 'ability', 'name'));

            // Guardar las preguntas generadas en la base de datos y obtener el ID de la pregunta
            $preguntaIdNombre = Pregunta::create($pregunta)->id;

            // Guardar las respuestas para cada pregunta
            foreach ($opcionesRespuesta as $opcionRespuesta) {
                Respuesta::create([
                    'pregunta_id' => $preguntaIdNombre,
                    'respuesta' => $opcionRespuesta,
                    'es_correcta' => ($opcionRespuesta === $pregunta['respuesta_correcta']) ? 1 : 0,
                ]);
            }
        }
    }

    public function pregunta($pokemonDetails, $nombresPokemon, $tipo)
    {
        [$nombre, $respuestaCorrecta] = $this->datosPregunta($tipo, $pokemonDetails);

        $pregunta = [
            'pregunta' => $nombre,
            'imagen' => $pokemonDetails['sprites']['front_default'],
            'respuesta_correcta' => $respuestaCorrecta,
        ];

        $opcionesRespuesta = $this->generarRespuestas($respuestaCorrecta, $nombresPokemon);
        return [$pregunta, $opcionesRespuesta];
    }

    public function datosPregunta($tipo, $pokemonDetails)
    {
        $nombre = "";
        switch ($tipo) {
            case 1:
                $nombre = "¿Cuál es el nombre de este Pokémon?";
                break;
            case 2:
                $nombre = "¿Cuál es el tipo de este Pokémon?";
                break;
        }

        $respuestaCorrecta = "";
        switch ($tipo) {
            case 1:
                $respuestaCorrecta = $pokemonDetails['name'];
                break;
            case 2:
                $respuestaCorrecta = $pokemonDetails['types'][0]['type']['name'];
        }
        return [$nombre, $respuestaCorrecta];
    }


    public function generarRespuestas($respuestaCorrecta, $nombresPokemon)
    {
        $respuestaIncorrectas = $this->generarOpcionesRespuestaIncorrectas($nombresPokemon, $respuestaCorrecta);
        // Mezclar las opciones de respuesta aleatoriamente, incluyendo la respuesta correcta y las incorrectas
        $opcionesRespuesta = [];
        foreach ($respuestaIncorrectas as $incorrecta) {
            $opcionesRespuesta[] = $incorrecta;
        }
        $opcionesRespuesta[] = $respuestaCorrecta;
        shuffle($opcionesRespuesta);
        return $opcionesRespuesta;
    }

    private function generarOpcionesRespuestaIncorrectas($nombresPokemon, $respuestaCorrecta, $cantidad = 3)
    {
        $opciones = [];

        // Obtener un número aleatorio de nombres de Pokémon diferentes a la respuesta correcta
        $nombresIncorrectos = array_diff($nombresPokemon, [$respuestaCorrecta]);

        // Tomar una cantidad específica de nombres incorrectos de forma aleatoria
        $nombresIncorrectos = array_rand($nombresIncorrectos, min($cantidad, count($nombresIncorrectos)));

        // Agregar los nombres incorrectos al array de opciones
        foreach ($nombresIncorrectos as $indice) {
            $opciones[] = $nombresPokemon[$indice];
        }

        return $opciones;
    }
}
