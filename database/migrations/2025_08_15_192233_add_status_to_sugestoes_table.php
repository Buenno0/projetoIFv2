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
        $table->integer('id_user_deleted')->nullable()->after('deleted_at');
        $table->integer('id_user_responded')->nullable()->after('data_resposta');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sugestoes', function (Blueprint $table) {
            //
        });
    }
};
