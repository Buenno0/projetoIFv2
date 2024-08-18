<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 65);
            $table->string('feedback', 10);
            $table->text('conteudo');
            $table->timestamp('created_at')->useCurrent();
            $table->boolean('visible')->default(true);
            $table->string('modificado_por', 65)->nullable();
            $table->timestamp('ultima_modificacao')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feedback');
    }
}
