<?php

use App\Http\Controllers\PokemonController;
use App\Http\Controllers\PreguntaController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::post('/save-welcome-data', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'nombre' => 'required|string',
        'genero' => 'required|in:masculino,femenino,otro',
    ]);

    $request->session()->put('nombre', $request->nombre);
    $request->session()->put('genero', $request->genero);

    return redirect()->route('quizz');
})->name('save_welcome_data');

Route::get('/quizz', [PreguntaController::class, 'quizz'])->name('quizz');

Route::get('/generar-preguntas', [PreguntaController::class, 'generarPreguntas']);

Route::get('/lista-pokemon', [PokemonController::class, 'index'])->name('lista-pokemon');

Route::get('/buscar', [PokemonController::class, 'buscar'])->name('buscar');
Route::get('/buscar-type', [PokemonController::class, 'buscarType'])->name('buscar-type');