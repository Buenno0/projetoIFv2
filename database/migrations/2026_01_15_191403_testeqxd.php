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
        Schema::create('criticas', function (Blueprint $table) {
            $table->id();
            
            // Conteúdo da crítica
            $table->text('conteudo');
            
            // Controle e Auditoria
            $table->boolean('visible')->default(true)->index();
            $table->string('modificado_por')->nullable();
            $table->timestamp('ultima_modificacao')->nullable();
            
            // Timestamps padrão (created_at e updated_at)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('criticas');
    }
};