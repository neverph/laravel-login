<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agrega las columnas necesarias para el Social Login (Google, GitHub)
     * a la tabla Usuario existente.
     */
    public function up(): void
    {
        Schema::table('Usuario', function (Blueprint $table) {
            // Permitir que Passwordd sea NULL (usuarios que solo usan social login)
            $table->string('Passwordd', 255)->nullable()->change();

            // Proveedor social: 'google', 'github', null
            $table->string('provider', 50)->nullable()->after('update_at');

            // ID único que da el proveedor al usuario
            $table->string('provider_id', 100)->nullable()->after('provider');

            // URL del avatar que devuelve el proveedor
            $table->string('avatar', 500)->nullable()->after('provider_id');
        });
    }

    public function down(): void
    {
        Schema::table('Usuario', function (Blueprint $table) {
            $table->dropColumn(['provider', 'provider_id', 'avatar']);
            $table->string('Passwordd', 20)->nullable(false)->change();
        });
    }
};

