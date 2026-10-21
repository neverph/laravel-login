@extends('layouts.auth')

@section('title', 'Crear cuenta')

@section('content')
    <h2>Crear cuenta</h2>
    <p class="subtitle">Rellena los datos para registrarte</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('register') }}" autocomplete="off">
        @csrf

        <div class="form-group">
            <label for="nombres">Nombre</label>
            <input type="text" id="nombres" name="nombres"
                   value="{{ old('nombres') }}" placeholder="Tu nombre" required>
            @error('nombres')
                <span class="error-msg">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="apellidos">Apellido</label>
            <input type="text" id="apellidos" name="apellidos"
                   value="{{ old('apellidos') }}" placeholder="Tu apellido" required>
            @error('apellidos')
                <span class="error-msg">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="correo">Correo electrónico</label>
            <input type="email" id="correo" name="correo"
                   value="{{ old('correo') }}" placeholder="ejemplo@correo.com" required>
            @error('correo')
                <span class="error-msg">{{ $message }}</span>
            @enderror
        </div>

        {{-- Teléfono con selector de país con banderas reales --}}
        <div class="form-group">
            <label>WhatsApp</label>
            <div class="phone-container">
                <div class="custom-country-picker" id="countryPicker">
                    <button type="button" class="country-btn" id="countryBtn" onclick="toggleCountryDropdown()">
                        <img id="selectedFlag" src="https://flagcdn.com/w40/mx.png" alt="MX" class="flag-icon">
                        <span id="selectedCode">+52</span>
                        <svg class="chevron-icon" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>

                    <div class="country-dropdown" id="countryDropdown">
                        <div class="country-search-box">
                            <input type="text" id="countrySearch" placeholder="Buscar país o prefijo..." oninput="filtrarPaises()" onclick="event.stopPropagation()">
                        </div>
                        <div class="country-list" id="countryList">
                            <!-- Lista generada por JS -->
                        </div>
                    </div>
                </div>

                <input type="tel" id="numero" name="numero"
                       value="{{ old('numero') }}"
                       placeholder="1234567890"
                       class="input-numero" required>
            </div>

            <!-- Campo oculto para enviar al controlador en formato internacional ej: +521234567890 -->
            <input type="hidden" id="telefono" name="telefono" value="{{ old('telefono') }}">

            @error('telefono')
                <span class="error-msg">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" placeholder="Mínimo 6 caracteres" required>
            @error('password')
                <span class="error-msg">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn">Registrarse</button>
    </form>

    {{-- ── Social Login ─────────────────────────────────────────────────────── --}}
    <div class="social-divider">
        <span>o regístrate con</span>
    </div>

    <div class="social-buttons">
        {{-- Google --}}
        <a href="{{ route('social.redirect', ['provider' => 'google', 'action' => 'register']) }}" class="social-btn google-btn">
            <svg width="18" height="18" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
            </svg>
            Google
        </a>

        {{-- GitHub --}}
        <a href="{{ route('social.redirect', ['provider' => 'github', 'action' => 'register']) }}" class="social-btn github-btn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/>
            </svg>
            GitHub
        </a>
    </div>

    <hr class="divider">

    <div class="links">
        ¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a>
    </div>

    <style>
        .phone-container {
            display: flex;
            gap: 8px;
            position: relative;
        }

        .custom-country-picker {
            position: relative;
            flex-shrink: 0;
        }

        .country-btn {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 10px 12px;
            background: #fafafa;
            border: 1.5px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.92rem;
            color: #222222;
            height: 100%;
            transition: border-color 0.2s, background 0.2s;
        }

        .country-btn:hover {
            border-color: #b0b0b0;
            background: #f5f5f5;
        }

        .country-btn:focus {
            outline: none;
            border-color: #5b8dee;
        }

        .flag-icon {
            width: 22px;
            height: 15px;
            object-fit: cover;
            border-radius: 2px;
            box-shadow: 0 0 1px rgba(0,0,0,0.4);
            display: block;
        }

        .chevron-icon {
            color: #777777;
            transition: transform 0.2s ease;
        }

        .custom-country-picker.open .chevron-icon {
            transform: rotate(180deg);
        }

        .country-dropdown {
            display: none;
            position: absolute;
            top: calc(100% + 5px);
            left: 0;
            width: 260px;
            max-height: 280px;
            background: #ffffff;
            border: 1.5px solid #e0e0e0;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
            z-index: 1000;
            overflow: hidden;
            flex-direction: column;
        }

        .custom-country-picker.open .country-dropdown {
            display: flex;
        }

        .country-search-box {
            padding: 8px 10px;
            border-bottom: 1px solid #eeeeee;
            background: #fafafa;
        }

        .country-search-box input {
            width: 100%;
            padding: 7px 10px;
            font-size: 0.85rem;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            background: #ffffff;
        }

        .country-search-box input:focus {
            outline: none;
            border-color: #5b8dee;
        }

        .country-list {
            overflow-y: auto;
            max-height: 220px;
        }

        .country-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            cursor: pointer;
            font-size: 0.86rem;
            color: #333333;
            transition: background 0.15s;
        }

        .country-item:hover {
            background: #f0f4ff;
            color: #1a56cc;
        }

        .country-item.active {
            background: #e8f0fe;
            font-weight: 600;
        }

        .country-item .item-name {
            flex: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .country-item .item-code {
            font-size: 0.82rem;
            color: #777777;
            font-weight: 500;
        }

        .input-numero {
            flex: 1;
            padding: 11px 14px;
            border: 1.5px solid #e0e0e0;
            border-radius: 8px;
            font-size: 0.92rem;
            background: #fafafa;
            color: #222222;
            transition: border-color 0.2s, background 0.2s;
        }

        .input-numero:focus {
            outline: none;
            border-color: #5b8dee;
            background: #ffffff;
        }

        /* ── Social Login ─────────────────────────────────────────────────── */
        .social-divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 20px 0 16px;
            gap: 12px;
        }

        .social-divider::before,
        .social-divider::after {
            content: '';
            flex: 1;
            border-top: 1.5px solid #f0f0f0;
        }

        .social-divider span {
            font-size: 0.78rem;
            color: #aaa;
            font-weight: 500;
            white-space: nowrap;
        }

        .social-buttons {
            display: flex;
            gap: 10px;
        }

        .social-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 0.88rem;
            font-weight: 600;
            text-decoration: none;
            border: 1.5px solid #e0e0e0;
            transition: border-color 0.2s, background 0.2s, transform 0.1s;
            background: #fff;
            color: #333;
        }

        .social-btn:hover {
            background: #f7f9ff;
            border-color: #b0c0e8;
        }

        .social-btn:active {
            transform: scale(0.98);
        }

        .github-btn:hover {
            background: #f6f6f6;
            border-color: #aaa;
        }
    </style>

    <script>
        const paises = [
            { code: '+52', iso: 'mx', name: 'México' },
            { code: '+51', iso: 'pe', name: 'Perú' },
            { code: '+1',  iso: 'us', name: 'Estados Unidos' },
            { code: '+54', iso: 'ar', name: 'Argentina' },
            { code: '+57', iso: 'co', name: 'Colombia' },
            { code: '+56', iso: 'cl', name: 'Chile' },
            { code: '+34', iso: 'es', name: 'España' },
            { code: '+593', iso: 'ec', name: 'Ecuador' },
            { code: '+58', iso: 've', name: 'Venezuela' },
            { code: '+502', iso: 'gt', name: 'Guatemala' },
            { code: '+503', iso: 'sv', name: 'El Salvador' },
            { code: '+504', iso: 'hn', name: 'Honduras' },
            { code: '+505', iso: 'ni', name: 'Nicaragua' },
            { code: '+506', iso: 'cr', name: 'Costa Rica' },
            { code: '+507', iso: 'pa', name: 'Panamá' },
            { code: '+591', iso: 'bo', name: 'Bolivia' },
            { code: '+595', iso: 'py', name: 'Paraguay' },
            { code: '+598', iso: 'uy', name: 'Uruguay' },
            { code: '+55',  iso: 'br', name: 'Brasil' },
            { code: '+44',  iso: 'gb', name: 'Reino Unido' },
            { code: '+49',  iso: 'de', name: 'Alemania' },
            { code: '+33',  iso: 'fr', name: 'Francia' },
            { code: '+39',  iso: 'it', name: 'Italia' }
        ];

        let selectedPais = paises[0];

        function renderCountryList(lista) {
            const listEl = document.getElementById('countryList');
            listEl.innerHTML = '';
            lista.forEach(p => {
                const item = document.createElement('div');
                item.className = 'country-item' + (p.code === selectedPais.code && p.iso === selectedPais.iso ? ' active' : '');
                item.onclick = () => selectCountry(p);
                item.innerHTML = `
                    <img src="https://flagcdn.com/w40/${p.iso}.png" alt="${p.iso.toUpperCase()}" class="flag-icon">
                    <span class="item-name">${p.name}</span>
                    <span class="item-code">${p.code}</span>
                `;
                listEl.appendChild(item);
            });
        }

        function toggleCountryDropdown() {
            const picker = document.getElementById('countryPicker');
            picker.classList.toggle('open');
            if (picker.classList.contains('open')) {
                const search = document.getElementById('countrySearch');
                search.value = '';
                renderCountryList(paises);
                search.focus();
            }
        }

        function selectCountry(pais) {
            selectedPais = pais;
            document.getElementById('selectedFlag').src = `https://flagcdn.com/w40/${pais.iso}.png`;
            document.getElementById('selectedFlag').alt = pais.iso.toUpperCase();
            document.getElementById('selectedCode').innerText = pais.code;
            document.getElementById('countryPicker').classList.remove('open');
            actualizarTelefono();
        }

        function filtrarPaises() {
            const query = document.getElementById('countrySearch').value.toLowerCase().trim();
            const filtrados = paises.filter(p =>
                p.name.toLowerCase().includes(query) ||
                p.code.includes(query) ||
                p.iso.includes(query)
            );
            renderCountryList(filtrados);
        }

        function actualizarTelefono() {
            const numeroInput = document.getElementById('numero');
            const cleanNumber = numeroInput.value.replace(/\D/g, '');
            document.getElementById('telefono').value = cleanNumber ? (selectedPais.code + cleanNumber) : '';
        }

        document.getElementById('numero').addEventListener('input', actualizarTelefono);

        // Cerrar dropdown al hacer click afuera
        document.addEventListener('click', function(e) {
            const picker = document.getElementById('countryPicker');
            if (picker && !picker.contains(e.target)) {
                picker.classList.remove('open');
            }
        });

        // Inicializar
        renderCountryList(paises);
        actualizarTelefono();
    </script>
@endsection
