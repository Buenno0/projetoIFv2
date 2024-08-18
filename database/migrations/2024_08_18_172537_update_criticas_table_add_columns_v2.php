<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCriticasTableAddColumnsV2 extends Migration
{
    /**
     * Execute as migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('criticas', function (Blueprint $table) {
            // Adicionar coluna somente se não existir
            if (!Schema::hasColumn('criticas', 'conteudo')) {
                $table->text('conteudo')->after('id');
            }
            if (!Schema::hasColumn('criticas', 'created_at')) {
                $table->timestamp('created_at')->useCurrent()->after('conteudo');
            }
            if (!Schema::hasColumn('criticas', 'visible')) {
                $table->boolean('visible')->default(true)->after('created_at');
            }
            if (!Schema::hasColumn('criticas', 'modificado_por')) {
                $table->string('modificado_por', 65)->nullable()->after('visible');
            }
            if (!Schema::hasColumn('criticas', 'ultima_modificacao')) {
                $table->timestamp('ultima_modificacao')->useCurrent()->useCurrentOnUpdate()->after('modificado_por');
            }
        });
    }

    /**
     * Reverter as migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('criticas', function (Blueprint $table) {
            $table->dropColumn(['conteudo', 'created_at', 'visible', 'modificado_por', 'ultima_modificacao']);
        });
    }
}
