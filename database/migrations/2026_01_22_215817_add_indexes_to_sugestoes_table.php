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
        Schema::table('sugestoes', function (Blueprint $table) {
            
            // 1. Otimiza o filtro de STATUS + ORDENAÇÃO (Filtro mais usado)
            // Cobre: where('status', $status)->orderBy('created_at')
            $table->index(['status', 'created_at'], 'idx_status_created');

            // 2. Otimiza o filtro "MINHAS ANÁLISES"
            // Cobre: where('id_user_analysing', $id)->where('status', 'em_analise')
            $table->index(['id_user_analysing', 'status'], 'idx_user_analysing_status');

            // 3. Otimiza a ordenação padrão (Carregamento inicial da página)
            // Cobre: orderBy('created_at', 'desc')
            $table->index('created_at', 'idx_created_at');

            // 4. Índices para as chaves estrangeiras (Performance nos Joins e Relacionamentos)
            // Caso você use $sugestao->usuarioQueAnalisou ou carregue via with()
            $table->index('id_user_responded', 'idx_user_responded');
            
            // Opcional: Índice para busca por nome (ajuda se não usar % no começo, ex: 'Lucas%')
            $table->index('nome', 'idx_nome');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sugestoes', function (Blueprint $table) {
            $table->dropIndex('idx_status_created');
            $table->dropIndex('idx_user_analysing_status');
            $table->dropIndex('idx_created_at');
            $table->dropIndex('idx_user_responded');
            $table->dropIndex('idx_nome');
        });
    }
};