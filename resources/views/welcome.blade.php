@extends('components/layout')

@section('title', 'PokeQuizz')

@section('content')
    <section class="welcome">
        <div class="card">
            <h2>Bienvendio, <br> ¿Quieres demostrar tu conocimiento Pokemon?</h2>
            <form method="POST" action="{{ route('save_welcome_data') }}">
                @csrf
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre" required>
                <br>
                <label for="genero">Género:</label>
                <select name="genero" id="genero" required>
                    <option value="masculino">Masculino</option>
                    <option value="femenino">Femenino</option>
                    <option value="otro">Otro</option>
                </select>
                <br>
                <div class="button">
                    <button type="submit">Guardar</button>
                </div>
            </form>
        </div>
    </section>
@endsection