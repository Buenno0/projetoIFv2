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
            // Adiciona coluna status            $table->boolean('respondido')->default(false)->after('conteudo');
            $table->timestamp('data_resposta')->nullable()->after('respondido');
            $table->timestamp('deleted_at')->nullable()->after('data_resposta');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sugestoes', function (Blueprint $table) {

            // Volta email para ser obrigatório
            $table->string('email', 125)->nullable(true)->change();
        });
    }
};
