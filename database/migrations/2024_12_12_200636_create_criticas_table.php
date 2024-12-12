<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
/**
* Run the migrations.
*/
public function up(): void
{
Schema::create('criticas', function (Blueprint $table) {
$table->id();
$table->text('conteudo'); // Conteúdo da crítica
$table->boolean('visible')->default(true); // Se a crítica está visível
$table->string('modificado_por', 65)->nullable(); // Usuário que modificou
$table->timestamp('ultima_modificacao')->default(DB::raw('CURRENT_TIMESTAMP'))->onUpdate(DB::raw('CURRENT_TIMESTAMP')); // Última modificação
$table->timestamps(); // created_at e updated_at
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
