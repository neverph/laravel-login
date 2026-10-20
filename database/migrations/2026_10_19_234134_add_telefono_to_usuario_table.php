<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // La tabla Usuario fue creada manualmente en MySQL,
        // así que usamos una sentencia directa para agregar la columna.
        \DB::statement('ALTER TABLE Usuario ADD COLUMN Telefono char(15) NULL AFTER Correo');
    }

    public function down(): void
    {
        \DB::statement('ALTER TABLE Usuario DROP COLUMN Telefono');
    }
};
