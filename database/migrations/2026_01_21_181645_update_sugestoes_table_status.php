<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sugestoes', function (Blueprint $table) {
            // 1. Criar as novas colunas
            // Usamos 'string' para o status (pendente, em_analise, respondida)
            // Colocamos 'index' para deixar as buscas por status rápidas no futuro
            $table->string('status')->default('pendente')->after('conteudo')->index();

            // ID de quem colocou em análise (nullable, pois nem todas estarão em análise)
            $table->unsignedBigInteger('id_user_analysing')->nullable()->after('id_user_responded');
            
            // Data da análise (para manter consistência com data_resposta)
            $table->dateTime('data_analise')->nullable()->after('data_resposta');

            // Se sua tabela de usuários for 'users', vale adicionar a chave estrangeira (opcional, mas recomendado)
            // $table->foreign('id_user_analysing')->references('id')->on('users');
        });

        // 2. Migrar os dados antigos (Para não perder quem já foi respondido)
        DB::table('sugestoes')->where('respondido', 1)->update(['status' => 'respondida']);
        DB::table('sugestoes')->where('respondido', 0)->update(['status' => 'pendente']);

        // 3. Remover a coluna antiga
        Schema::table('sugestoes', function (Blueprint $table) {
            $table->dropColumn('respondido');
        });
    }

    public function down()
    {
        // Reverter a mudança caso dê problema
        Schema::table('sugestoes', function (Blueprint $table) {
            $table->tinyInteger('respondido')->default(0);
        });

        // Recuperar dados (O status 'em_analise' voltará a ser 'não respondido' / 0)
        DB::table('sugestoes')->where('status', 'respondida')->update(['respondido' => 1]);
        DB::table('sugestoes')->where('status', '!=', 'respondida')->update(['respondido' => 0]);

        Schema::table('sugestoes', function (Blueprint $table) {
            $table->dropColumn(['status', 'id_user_analysing', 'data_analise']);
        });
    }
};