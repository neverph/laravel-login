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
        <span class="badge">Sesión activa</span>
    </div>
</div>

</body>
</html>
