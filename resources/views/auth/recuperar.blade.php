@extends('layouts.auth')

@section('title', 'Recuperar contraseña')

@section('content')
    <h2>Recuperar contraseña</h2>
    <p class="subtitle">Te enviaremos un código por WhatsApp</p>

    @if(session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif

    <form method="POST" action="{{ route('otp.solicitar') }}">
        @csrf

        <div class="form-group">
            <label for="correo">Correo electrónico</label>
            <input type="email" id="correo" name="correo"
                   value="{{ old('correo') }}" placeholder="ejemplo@correo.com" autofocus>
            @error('correo')
                <span class="error-msg">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn">Enviar código</button>
    </form>

    <hr class="divider">

    <div class="links">
        <a href="{{ route('login') }}">← Volver al login</a>
    </div>
@endsection
