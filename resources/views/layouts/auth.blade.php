<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login')</title>
    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif !important;
        }

        body {
            font-family: 'Poppins', sans-serif !important;
            background: #eef1f7;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #222;
        }

        .card {
            background: #ffffff;
            border-radius: 14px;
            padding: 40px 42px;
            width: 100%;
            max-width: 430px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.07);
        }

        .card h2 {
            margin-bottom: 6px;
            font-size: 1.5rem;
            font-weight: 700 !important;
            color: #1a1a2e;
            letter-spacing: -0.01em;
            font-family: 'Poppins', sans-serif !important;
        }

        .card .subtitle {
            font-size: 0.86rem;
            color: #888888;
            margin-bottom: 26px;
            font-weight: 400;
        }

        .form-group {
            margin-bottom: 18px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 7px;
            font-size: 0.76rem;
            font-weight: 600;
            color: #555555;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .form-group input {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid #e0e0e0;
            border-radius: 8px;
            font-size: 0.92rem;
            color: #222222;
            background: #fafafa;
            transition: border-color 0.2s, background 0.2s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #5b8dee;
            background: #ffffff;
        }

        .form-group input::placeholder {
            color: #bbbbbb;
        }

        .error-msg {
            color: #e53935;
            font-size: 0.78rem;
            margin-top: 5px;
            display: block;
            font-weight: 500;
        }

        .btn {
            width: 100%;
            padding: 12px;
            background: #5b8dee;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 0.94rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 8px;
            transition: background 0.2s, transform 0.1s;
            letter-spacing: 0.02em;
        }

        .btn:hover {
            background: #4072d4;
        }

        .btn:active {
            transform: scale(0.98);
        }

        .divider {
            border: none;
            border-top: 1.5px solid #f0f0f0;
            margin: 24px 0 20px;
        }

        .links {
            text-align: center;
            font-size: 0.84rem;
            color: #888888;
        }

        .links a {
            color: #5b8dee;
            text-decoration: none;
            font-weight: 600;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 11px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.86rem;
            font-weight: 500;
        }

        .alert-success {
            background: #e8f5e9;
            color: #2e7d32;
            border-left: 4px solid #43a047;
        }

        .alert-info {
            background: #e8f0fe;
            color: #1a56cc;
            border-left: 4px solid #5b8dee;
        }

        .alert-error {
            background: #ffebee;
            color: #c62828;
            border-left: 4px solid #ef5350;
        }
    </style>
</head>
<body>
    <div class="card">
        @yield('content')
    </div>
</body>
</html>
