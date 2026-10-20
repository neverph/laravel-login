<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('codigos_otp', function (Blueprint $table) {
            $table->id();
            $table->string('correo', 50);
            $table->string('codigo', 10);
            $table->boolean('usado')->default(false);
            $table->timestamp('expira_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('codigos_otp');
    }
};
