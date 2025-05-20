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
        Schema::create('enderecos', function (Blueprint $table) {
            $table->id();
            $table->string('cep')->max(8);
            $table->string('logradouro')->max(100);
            $table->string('complemento')->max(255);
            $table->string('bairro')->max(100);
            $table->string('cidade')->max(100);
            $table->string('uf')->max(2);
            $table->string('numero')->max(12);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enderecos');
    }
};
