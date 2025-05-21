<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pontos_paradas', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->max(100);
            $table->foreignId('endereco_id')->constrained()->nullOnDelete();
            $table->string('complemento')->nullable()->max(100);
            $table->string('numero')->nullable()->max(100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pontos_paradas');
    }
};
