<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    // Proveedores permitidos
    private array $proveedoresPermitidos = ['google', 'github'];

    // ── REDIRECT ───────────────────────────────────────────────────────────────
    // Redirige al usuario a la pantalla de OAuth del proveedor

    public function redirect(string $provider)
    {
        if (!in_array($provider, $this->proveedoresPermitidos)) {
            abort(404);
        }

        return Socialite::driver($provider)->redirect();
    }

    // ── CALLBACK ───────────────────────────────────────────────────────────────
    // El proveedor regresa aquí con los datos del usuario autenticado

    public function callback(string $provider)
    {
        if (!in_array($provider, $this->proveedoresPermitidos)) {
            abort(404);
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Throwable $e) {
            return redirect()->route('login')
                ->withErrors(['correo' => 'No se pudo autenticar con ' . ucfirst($provider) . '. Inténtalo de nuevo.']);
        }

        // El email es necesario; GitHub puede tenerlo privado
        $email = $socialUser->getEmail();

        if (!$email) {
            return redirect()->route('login')
                ->withErrors(['correo' => 'Tu cuenta de ' . ucfirst($provider) . ' no tiene un correo público. Usa otro método de acceso.']);
        }

        // ── 1. Buscar usuario por provider_id (ya inició sesión antes con social)
        $usuario = DB::table('Usuario')
            ->where('provider', $provider)
            ->where('provider_id', (string) $socialUser->getId())
            ->first();

        // ── 2. Si no existe por provider_id, buscar por correo
        //       (puede que ya tenga cuenta con email/password)
        if (!$usuario) {
            $usuario = DB::table('Usuario')
                ->where('Correo', $email)
                ->first();

            if ($usuario) {
                // Vinculamos las columnas sociales a la cuenta existente
                DB::table('Usuario')
                    ->where('id', $usuario->id)
                    ->update([
                        'provider'    => $provider,
                        'provider_id' => (string) $socialUser->getId(),
                        'avatar'      => $socialUser->getAvatar() ?? $usuario->avatar ?? null,
                        'update_at'   => now(),
                    ]);

                // Refrescamos el objeto
                $usuario = DB::table('Usuario')->where('id', $usuario->id)->first();
            }
        }

        // ── 3. Si tampoco existe por correo, creamos una cuenta nueva
        if (!$usuario) {
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
        }

        // ── 4. Guardar en sesión con la misma estructura que usa AuthController
        Session::put('usuario', (array) $usuario);

        return redirect()->route('dashboard');
    }
}

