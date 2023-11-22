<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class PokemonController extends Controller
{
    public function index(Request $request)
    {
        if (session('dataPokemones') == null) {
            // Obtener la lista de todos los Pokémon de la PokeAPI
            $response = Http::get('https://pokeapi.co/api/v2/pokemon/');
            $pokemonList = $response->json()['results'];
            $dataPokemones = $this->dataPokemon($pokemonList);

            $request->session()->put('dataPokemones', $dataPokemones);
        }

        if(session('pokemontype') !== null){
            Session::forget('pokemontype');
        }
        $dataPokemones = session('dataPokemones');

        return view('lista-pokemon')->with('pokemones', $dataPokemones);
    }

    public function buscar(Request $request)
    {
        $q =  strtolower($request->input('q'));
        $pokemonList = session('dataPokemones');

        $pokemones = [];
        $msg = false;
        foreach ($pokemonList as $pokemon) {
            if (stristr($pokemon['name'], $q)) {
                $pokemones[] = $pokemon;
                $msg = true;
            }
        }
        if (count($pokemones) < 1) {
            $response = Http::get("https://pokeapi.co/api/v2/pokemon/$q");
            if ($response->json() !== null) {
                $pokemonList = $response->json();
                $pokemones = $this->dataPokemon([$pokemonList], 1);
                $msg = true;
            } else {
                $pokemones = session('dataPokemones');
            }
        }

        return view('lista-pokemon')
            ->with('pokemones', $pokemones)
            ->with('msg', $msg);
    }

    public function buscarType(Request $request)
    {
        $type = strtolower($request->input('type'));
        if(session('pokemontype') == null){
            $response = Http::get("https://pokeapi.co/api/v2/type/$type");
            $pokemonList = $response->json()['pokemon'];
            $request->session()->put('pokemontype', $pokemonList);
        }else{
            $pokemonList = session('pokemontype');
        }
        
        $chunkSize = 12;
        $currentChunk = $request->input('chunk') ?? 0;
        $start = $currentChunk * $chunkSize;

        $currentPokemonList = array_slice($pokemonList, $start, $chunkSize);
        $dataPokemones = $this->dataPokemon($currentPokemonList, 3);
        return view('lista-pokemon', [
            'pokemones' => $dataPokemones,
            'currentChunk' => $currentChunk,
            'totalChunks' => ceil(count($pokemonList) / $chunkSize),
            'type' => $type
        ]);
    }

    public function dataPokemon($pokemonList, $tipo = 0): array
    {
        $dataPokemones = [];

        foreach ($pokemonList as $pokemon) {
            $pokemonDetails = $pokemon;
            if ($tipo == 0) {
                $pokemonResponse = Http::get($pokemon['url']);
                $pokemonDetails = $pokemonResponse->json();
            }
            if ($tipo == 3) {
                $pokemonResponse = Http::get($pokemon['pokemon']['url']);
                $pokemonDetails = $pokemonResponse->json();
            }
            $data = [
                'name' => $pokemonDetails['name'],
                'foto' => $pokemonDetails['sprites']['front_default'],
                'types' => $this->getType($pokemonDetails['types']),
                'stats' => $this->getStats($pokemonDetails['stats']),
                'colorType' => $this->colorType($pokemonDetails['types'][0]['type']['name']),
            ];
            $dataPokemones[] = $data;
        }
        return $dataPokemones;
    }

    public function getStats($stats): array
    {
        $data = [];
        foreach ($stats as $stat) {
            switch ($stat["stat"]["name"]) {
                case 'hp':
                    $data['hp'] = $stat["base_stat"];
                    break;
                case 'attack':
                    $data['attack'] = $stat["base_stat"];
                    break;
                case 'defense':
                    $data['defense'] = $stat["base_stat"];
                    break;
                case 'speed':
                    $data['speed'] = $stat["base_stat"];
            }
        }
        return $data;
    }

    public function getType($types)
    {
        $tipos = [];

        foreach ($types as $type) {
            $data = [
                'type' => $type['type']['name'],
                'color' => $this->colorType($type['type']['name']),
            ];
            $tipos[] =  $data;
        }
        return $tipos;
    }

    public function colorType($type): String
    {
        switch (ucwords($type)) {
            case 'Normal':
                $color = '#A8A77A';
                break;
            case 'Fire':
                $color = '#EE8130';
                break;
            case 'Water':
                $color = '#6390F0';
                break;
            case 'Electric':
                $color = '#F7D02C';
                break;
            case 'Grass':
                $color = '#7AC74C';
                break;
            case 'Ice':
                $color = '#96D9D6';
                break;
            case 'Fighting':
                $color = '#C22E28';
                break;
            case 'Poison':
                $color = '#A33EA1';
                break;
            case 'Ground':
                $color = '#E2BF65';
                break;
            case 'Flying':
                $color = '#A98FF3';
                break;
            case 'Psychic':
                $color = '#F95587';
                break;
            case 'Bug':
                $color = '#A6B91A';
                break;
            case 'Rock':
                $color = '#B6A136';
                break;
            case 'Ghost':
                $color = '#735797';
                break;
            case 'Dragon':
                $color = '#6F35FC';
                break;
            case 'Dark':
                $color = '#705746';
                break;
            case 'Steel':
                $color = '#B7B7CE';
                break;
            case 'Fairy':
                $color = '#D685AD';
        }
        return $color;
    }
}
