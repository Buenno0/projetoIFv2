<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sugestoes', function (Blueprint $table) {
            if (!Schema::hasColumn('sugestoes', 'visible')) {
                $table->boolean('visible')->default(true)->index();
            }
            if (!Schema::hasColumn('sugestoes', 'deleted_at')) {
                $table->timestamp('deleted_at')->nullable()->index();
            }
            if (!Schema::hasColumn('sugestoes', 'id_user_deleted')) {
                $table->unsignedBigInteger('id_user_deleted')->nullable()->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('sugestoes', function (Blueprint $table) {
            if (Schema::hasColumn('sugestoes', 'id_user_deleted')) {
                $table->dropColumn('id_user_deleted');
            }
            if (Schema::hasColumn('sugestoes', 'deleted_at')) {
                $table->dropColumn('deleted_at');
            }
            if (Schema::hasColumn('sugestoes', 'visible')) {
                $table->dropColumn('visible');
            }
        });
    }
};
