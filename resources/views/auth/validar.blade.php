@extends('layouts.auth')

@section('title', 'Validar código')

@section('content')
    <h2>Ingresa tu código</h2>
    <p class="subtitle">Revisa tu WhatsApp — el código expira en 10 min</p>

    @if(session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif

    <form method="POST" action="{{ route('otp.validar') }}">
        @csrf

        <div class="form-group">
            <label for="codigo">Código OTP</label>
            <input type="text" id="codigo" name="codigo"
                   placeholder="· · · · · ·" maxlength="6" autofocus
                   style="letter-spacing: 10px; font-size: 1.5rem; text-align: center; font-weight: 700; padding: 14px;">
            @error('codigo')
                <span class="error-msg">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn">Verificar código</button>
    </form>

    <hr class="divider">

    <div class="links">
        <a href="{{ route('otp.solicitar') }}">Reenviar código</a>
        &nbsp;&nbsp;·&nbsp;&nbsp;
        <a href="{{ route('login') }}">Cancelar</a>
    </div>
@endsection
