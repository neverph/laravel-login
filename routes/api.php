<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OtpController;

// Redirigir raíz al login
Route::get('/', fn() => redirect()->route('login'));

// ── Autenticación ──────────────────────────────────────────────────────────────
Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',   [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register',[AuthController::class, 'register']);

Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

// ── Recuperar contraseña por OTP ───────────────────────────────────────────────
Route::get('/recuperar',        [OtpController::class, 'showSolicitar'])->name('otp.solicitar');
Route::post('/recuperar',       [OtpController::class, 'solicitar']);

Route::get('/recuperar/validar', [OtpController::class, 'showValidar'])->name('otp.validar');
Route::post('/recuperar/validar',[OtpController::class, 'validar']);

Route::get('/recuperar/password', [OtpController::class, 'showNuevaPassword'])->name('otp.nueva_password');
Route::post('/recuperar/password',[OtpController::class, 'nuevaPassword']);

