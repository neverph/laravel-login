<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class OtpController extends Controller
{
    // ── SOLICITAR CÓDIGO ───────────────────────────────────────────────────────

    public function showSolicitar()
    {
        return view('auth.recuperar');
    }

    public function solicitar(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
        ]);

        // Verificar que el correo existe y obtener el teléfono
        $usuario = DB::selectOne(
            'SELECT id, Telefono FROM Usuario WHERE Correo = ?',
            [$request->correo]
        );

        if (!$usuario) {
            return back()
                ->withErrors(['correo' => 'No encontramos una cuenta con ese correo.'])
                ->withInput();
        }

        if (empty($usuario->Telefono)) {
            return back()
                ->withErrors(['correo' => 'Esta cuenta no tiene un número de WhatsApp registrado.'])
                ->withInput();
        }

        // Generar código de 6 dígitos
        $codigo = strval(rand(100000, 999999));
        $expira = now()->addMinutes(10);

        // Invalidar códigos anteriores del mismo correo
        DB::table('codigos_otp')
            ->where('correo', $request->correo)
            ->where('usado', false)
            ->update(['usado' => true]);

        // Guardar nuevo código
        DB::table('codigos_otp')->insert([
            'correo'     => $request->correo,
            'codigo'     => $codigo,
            'usado'      => false,
            'expira_at'  => $expira,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Enviar por WhatsApp
        $enviado = $this->enviarWhatsapp($usuario->Telefono, $codigo);

        if (!$enviado) {
            return back()->withErrors([
                'correo' => 'No se pudo enviar el código. Intenta de nuevo más tarde.',
            ]);
        }

        Session::put('otp_correo', $request->correo);

        return redirect()->route('otp.validar')->with('info', 'Código enviado por WhatsApp.');
    }

    // ── VALIDAR CÓDIGO ─────────────────────────────────────────────────────────

    public function showValidar()
    {
        if (!Session::has('otp_correo')) {
            return redirect()->route('otp.solicitar');
        }

        return view('auth.validar');
    }

    public function validar(Request $request)
    {
        $request->validate([
            'codigo' => 'required|digits:6',
        ]);

        $correo = Session::get('otp_correo');

        $otp = DB::table('codigos_otp')
            ->where('correo', $correo)
            ->where('codigo', $request->codigo)
            ->where('usado', false)
            ->where('expira_at', '>=', now())
            ->latest()
            ->first();

        if (!$otp) {
            return back()->withErrors(['codigo' => 'El código es incorrecto o ya expiró.']);
        }

        DB::table('codigos_otp')->where('id', $otp->id)->update(['usado' => true]);

        Session::put('otp_verificado', true);

        return redirect()->route('otp.nueva_password');
    }

    // ── NUEVA CONTRASEÑA ───────────────────────────────────────────────────────

    public function showNuevaPassword()
    {
        if (!Session::has('otp_verificado') || !Session::has('otp_correo')) {
            return redirect()->route('login');
        }

        return view('auth.nueva_password');
    }

    public function nuevaPassword(Request $request)
    {
        $request->validate([
            'password'  => 'required|min:6|max:20',
            'confirmar' => 'required|same:password',
        ]);

        $correo = Session::get('otp_correo');

        DB::statement('CALL sp_Usuario_Update(?, ?)', [
            $correo,
            $request->password,
        ]);

        Session::forget(['otp_correo', 'otp_verificado']);

        return redirect()->route('login')->with('success', 'Contraseña actualizada correctamente.');
    }

    // ── HELPER: ENVÍO WHATSAPP VÍA ULTRAMSG ───────────────────────────────────

    private function enviarWhatsapp(string $telefono, string $codigo): bool
    {
        $instance = config('services.ultramsg.instance');
        $token    = config('services.ultramsg.token');

        if (empty($instance) || $instance === 'tu_instance_id') {
            // Sin credenciales → solo log (modo desarrollo)
            Log::info("[OTP] Teléfono: {$telefono} | Código: {$codigo} (Ultramsg no configurado)");
            return true;
        }

        $url  = "https://api.ultramsg.com/{$instance}/messages/chat";
        $body = http_build_query([
            'token' => $token,
            'to'    => $telefono,
            'body'  => "Tu código de verificación es: *{$codigo}*\nExpira en 10 minutos.",
        ]);

        $context = stream_context_create([
            'http' => [
                'method'  => 'POST',
                'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
                'content' => $body,
                'timeout' => 10,
            ],
        ]);

        try {
            $respuesta = file_get_contents($url, false, $context);
            Log::info("[OTP] Ultramsg | Teléfono: {$telefono} | Respuesta: {$respuesta}");
            return true;
        } catch (\Exception $e) {
            Log::error("[OTP] Error Ultramsg: " . $e->getMessage());
            return false;
        }
    }
}
