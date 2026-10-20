<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Poppins', sans-serif;
            background: #eef1f7;
            min-height: 100vh;
        }

        header {
            background: #fff;
            border-bottom: 1.5px solid #eee;
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand {
            font-size: 1.05rem;
            font-weight: 700;
            color: #1a1a2e;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-info span {
            font-size: .88rem;
            color: #777;
            font-weight: 500;
        }

        .avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e0e0e0;
        }

        .avatar-placeholder {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #5b8dee;
            color: #fff;
            font-size: 0.85rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .provider-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.72rem;
            color: #777;
            background: #f5f5f5;
            border: 1px solid #e0e0e0;
            border-radius: 20px;
            padding: 3px 10px;
            font-weight: 500;
        }

        .logout-btn {
            background: transparent;
            border: 1.5px solid #e0e0e0;
            color: #777;
            padding: 7px 18px;
            border-radius: 8px;
            cursor: pointer;
            font-size: .85rem;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            transition: border-color .2s, color .2s;
        }

        .logout-btn:hover {
            border-color: #ef5350;
            color: #ef5350;
        }

        .container {
            max-width: 680px;
            margin: 60px auto;
            padding: 0 16px;
        }

        .welcome-card {
            background: #fff;
            border-radius: 12px;
            padding: 36px 40px;
            box-shadow: 0 4px 24px rgba(0,0,0,.07);
        }

        .welcome-card h1 {
            font-size: 1.4rem;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 10px;
        }

        .welcome-card p {
            color: #999;
            font-size: .9rem;
            line-height: 1.7;
            font-weight: 400;
        }

        .welcome-card p strong {
            color: #555;
            font-weight: 600;
        }

        .badge {
            display: inline-block;
            margin-top: 20px;
            background: #e8f0fe;
            color: #1a56cc;
            border-radius: 20px;
            padding: 5px 16px;
            font-size: .78rem;
            font-weight: 600;
            letter-spacing: .03em;
        }
    </style>
</head>
<body>

<header>
    <span class="brand">Mi App</span>
    <div class="user-info">
        {{-- Avatar: si tiene URL (social login) la muestra, si no, inicial del nombre --}}
        @if(!empty($usuario['avatar']))
            <img src="{{ $usuario['avatar'] }}" alt="avatar" class="avatar">
        @else
            <div class="avatar-placeholder">
                {{ strtoupper(mb_substr($usuario['Nombres'], 0, 1)) }}
            </div>
        @endif

        <span>{{ $usuario['Nombres'] }}</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">Cerrar sesión</button>
        </form>
    </div>
</header>

<div class="container">
    <div class="welcome-card">
        <h1>¡Hola, {{ explode(' ', $usuario['Nombres'])[0] }}! 👋</h1>
        <p>Has iniciado sesión correctamente.</p>
        <p>Tu correo registrado es <strong>{{ $usuario['Correo'] }}</strong>.</p>
        @if(!empty($usuario['provider']))
            <p style="margin-top:8px; font-size:.85rem; color:#aaa;">
                Cuenta vinculada con
                <span class="provider-badge">
                    @if($usuario['provider'] === 'google')
                        <svg width="12" height="12" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/></svg>
                    @elseif($usuario['provider'] === 'github')
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="#333"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
                    @endif
                    {{ ucfirst($usuario['provider']) }}
                </span>
            </p>
        @endif
        <span class="badge">Sesión activa</span>
    </div>
</div>

</body>
</html>
