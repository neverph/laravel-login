@extends('layouts.auth')

@section('title', 'Crear cuenta')

@section('content')
    <h2>Crear cuenta</h2>
    <p class="subtitle">Rellena los datos para registrarte</p>

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
