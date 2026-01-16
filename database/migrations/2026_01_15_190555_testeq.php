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
        Schema::create('sugestoes', function (Blueprint $table) {
            $table->id();
            
            // Dados da Sugestão
            $table->string('nome');
            $table->string('email');
            $table->text('conteudo');
            
            // Status de Resposta
            $table->boolean('respondido')->default(false);
            $table->datetime('data_resposta')->nullable();
            $table->unsignedBigInteger('id_user_responded')->nullable();
            $table->string('respondido_por')->nullable(); // Nome ou string conforme seu model
            
            // Controle de Visibilidade e Logs
            $table->boolean('visible')->default(true)->index();
            $table->unsignedBigInteger('id_user_deleted')->nullable()->index();
            $table->string('modificado_por')->nullable();
            
            // Timestamps (Cria created_at e updated_at automaticamente)
            $table->timestamps();
            
            // Soft Deletes (Cria a coluna deleted_at que está no seu cast)
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sugestoes');
    }
};