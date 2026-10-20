<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\UniqueConstraintViolationException;

class AuthController extends Controller
{
    // ── LOGIN ──────────────────────────────────────────────────────────────────

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'correo'    => 'required|email',
            'password'  => 'required',
        ]);

        $usuario = DB::selectOne('CALL sp_Usuario_Login(?, ?)', [
            $request->correo,
            $request->password,
        ]);

        if (!$usuario) {
            return back()->withErrors(['correo' => 'Correo o contraseña incorrectos.'])->withInput();
        }

        Session::put('usuario', (array) $usuario);

        return redirect()->route('dashboard');
    }

    public function logout()
    {
        Session::forget('usuario');
        return redirect()->route('login');
    }

    // ── REGISTER ───────────────────────────────────────────────────────────────

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nombres'   => 'required|string|max:50',
            'apellidos' => 'required|string|max:50',
            'correo'    => 'required|email|max:100',
            'telefono'  => 'required|string|max:15',
            'password'  => 'required|min:6|max:20',
        ], [
            'telefono.required' => 'Ingresa tu número de WhatsApp.',
        ]);

        $nombreCompleto = $request->nombres . ' ' . $request->apellidos;

        try {
            DB::statement('CALL sp_Usuario_Insert(?, ?, ?)', [
                $nombreCompleto,
                $request->correo,
                $request->password,
            ]);
        } catch (UniqueConstraintViolationException) {
            return back()
                ->withErrors(['correo' => 'Ese correo ya está registrado. ¿Quieres iniciar sesión?'])
                ->withInput();
        }

        // Guardar el teléfono (el SP no lo incluye, lo actualizamos directo)
        DB::table('Usuario')
            ->where('Correo', $request->correo)
            ->update(['Telefono' => $request->telefono]);

        return redirect()->route('login')->with('success', 'Cuenta creada. Ya puedes iniciar sesión.');
    }

    // ── DASHBOARD ──────────────────────────────────────────────────────────────

    public function dashboard()
    {
        if (!Session::has('usuario')) {
            return redirect()->route('login');
        }

        return view('auth.dashboard', ['usuario' => Session::get('usuario')]);
    }
}

