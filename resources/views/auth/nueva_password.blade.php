@extends('layouts.auth')

@section('title', 'Nueva contraseña')

@section('content')
    <h2>Nueva contraseña</h2>
    <p class="subtitle">Elige una contraseña segura para tu cuenta</p>

    <form method="POST" action="{{ route('otp.nueva_password') }}">
        @csrf

        <div class="form-group">
            <label for="password">Nueva contraseña</label>
            <input type="password" id="password" name="password" placeholder="Mínimo 6 caracteres">
            @error('password')
                <span class="error-msg">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="confirmar">Confirmar contraseña</label>
            <input type="password" id="confirmar" name="confirmar" placeholder="Repite la contraseña">
            @error('confirmar')
                <span class="error-msg">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn">Guardar contraseña</button>
    </form>
@endsection
