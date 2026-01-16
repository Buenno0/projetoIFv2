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
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            
            // Campos de texto
            $table->string('nome');
            $table->string('feedback'); // Título ou resumo
            $table->text('conteudo');
            
            // Controle e Auditoria
            $table->boolean('visible')->default(true)->index();
            $table->string('modificado_por')->nullable();
            
            // Datas manuais (conforme definido no seu Model)
            $table->timestamp('created_at')->nullable();
            $table->timestamp('ultima_modificacao')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};