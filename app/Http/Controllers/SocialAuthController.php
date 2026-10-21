<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    // Proveedores permitidos
    private array $proveedoresPermitidos = ['google', 'github'];

    // ── REDIRECT ───────────────────────────────────────────────────────────────
    // Redirige al usuario a la pantalla de OAuth del proveedor
    public function redirect(Request $request, string $provider)
    {
        if (!in_array($provider, $this->proveedoresPermitidos)) {
            abort(404);
        }

        // Detectar si la intención es iniciar sesión o registrarse
        $action = $request->query('action', 'login');
        if (!in_array($action, ['login', 'register'])) {
            $action = 'login';
        }

        // Guardamos la intención en la sesión para el callback
        Session::put('social_auth_action', $action);

        return Socialite::driver($provider)->redirect();
    }

    // ── CALLBACK ───────────────────────────────────────────────────────────────
    // El proveedor regresa aquí con los datos del usuario autenticado
    public function callback(Request $request, string $provider)
    {
        if (!in_array($provider, $this->proveedoresPermitidos)) {
            abort(404);
        }

        $action = Session::pull('social_auth_action', 'login');
        $nombreProveedor = ucfirst($provider);

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Throwable $e) {
            $rutaRetorno = ($action === 'register') ? 'register' : 'login';
            return redirect()->route($rutaRetorno)
                ->with('error', "No se pudo autenticar con {$nombreProveedor}. Inténtalo de nuevo.");
        }

        // El email es necesario; GitHub puede tenerlo privado
        $email = $socialUser->getEmail();

        if (!$email) {
            $rutaRetorno = ($action === 'register') ? 'register' : 'login';
            return redirect()->route($rutaRetorno)
                ->with('error', "Tu cuenta de {$nombreProveedor} no tiene un correo público visible. Usa otro método de acceso.");
        }

        // ── 1. Buscar si ya existe el usuario (por provider_id o por Correo)
        $usuario = DB::table('Usuario')
            ->where('provider', $provider)
            ->where('provider_id', (string) $socialUser->getId())
            ->first();

        if (!$usuario) {
            $usuario = DB::table('Usuario')
                ->where('Correo', $email)
                ->first();
        }

        // ════════════════════════════════════════════════════════════════════════
        // FLUJO DE REGISTRO (viene desde /register)
        // ════════════════════════════════════════════════════════════════════════
        if ($action === 'register') {
            // Filtro: Si la cuenta YA existe, no permitir duplicar ni crearla de nuevo
            if ($usuario) {
                return redirect()->route('login')
                    ->with('error', "Ya existe una cuenta con el correo {$email}. Por favor inicia sesión.");
            }

            // Si no existe, crear la nueva cuenta
            $nombre = $socialUser->getName()
                ?? $socialUser->getNickname()
                ?? explode('@', $email)[0];

            $id = DB::table('Usuario')->insertGetId([
                'Nombres'     => $nombre,
                'Correo'      => $email,
                'Passwordd'   => null,           // sin contraseña (social login)
                'Telefono'    => null,
                'provider'    => $provider,
                'provider_id' => (string) $socialUser->getId(),
                'avatar'      => $socialUser->getAvatar() ?? null,
                'create_at'   => now(),
                'update_at'   => now(),
            ]);

            $usuario = DB::table('Usuario')->where('id', $id)->first();
            Session::put('usuario', (array) $usuario);

            return redirect()->route('dashboard');
        }

        // ════════════════════════════════════════════════════════════════════════
        // FLUJO DE LOGIN (viene desde /login)
        // ════════════════════════════════════════════════════════════════════════
        // Filtro: Si la cuenta NO existe en la base de datos, rechazar y pedir registrarse
        if (!$usuario) {
            return redirect()->route('register')
                ->with('error', "No existe ninguna cuenta registrada con el correo {$email}. Por favor regístrate primero.");
        }

        // Si ya existe la cuenta, vinculamos las credenciales sociales si no las tenía
        if (empty($usuario->provider) || empty($usuario->provider_id)) {
            DB::table('Usuario')
                ->where('id', $usuario->id)
                ->update([
                    'provider'    => $provider,
                    'provider_id' => (string) $socialUser->getId(),
                    'avatar'      => $socialUser->getAvatar() ?? $usuario->avatar ?? null,
                    'update_at'   => now(),
                ]);

            $usuario = DB::table('Usuario')->where('id', $usuario->id)->first();
        }

        // Iniciar sesión
        Session::put('usuario', (array) $usuario);

        return redirect()->route('dashboard');
    }
}

