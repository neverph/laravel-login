@extends('layouts.auth')

@section('title', 'Iniciar sesión')

@section('content')
    <h2>Bienvenido</h2>
    <p class="subtitle">Inicia sesión para continuar</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label for="correo">Correo electrónico</label>
            <input type="email" id="correo" name="correo"
                   value="{{ old('correo') }}" placeholder="ejemplo@correo.com" autofocus>
            @error('correo')
                <span class="error-msg">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" placeholder="••••••••">
            @error('password')
                <span class="error-msg">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn">Iniciar sesión</button>
    </form>

    <hr class="divider">

    <div class="links">
        <a href="{{ route('otp.solicitar') }}">¿Olvidaste tu contraseña?</a>
        &nbsp;&nbsp;·&nbsp;&nbsp;
        <a href="{{ route('register') }}">Crear cuenta</a>
    </div>
@endsection
