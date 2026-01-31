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
            // "sugestao" é o padrão para manter compatibilidade com os dados atuais
            $table->string('tipo')->default('sugestao')->after('status'); 
            
            // Avaliação de 1 a 5 (para elogios ou feedbacks)
            $table->tinyInteger('avaliacao')->unsigned()->nullable()->after('tipo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sugestoes', function (Blueprint $table) {
            $table->dropColumn(['tipo', 'avaliacao']);
        });
    }
};
