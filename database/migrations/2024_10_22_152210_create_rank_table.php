<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRankTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rank', function (Blueprint $table) {
            $table->id(); // Chave primária
            $table->string('name'); // Nome do participante ou equipe
            $table->integer('points'); // Pontos
            $table->unsignedBigInteger('tournament_id'); // ID do torneio
            $table->timestamps();

            // Foreign key constraint, assumindo que a tabela 'tournaments' existe
            $table->foreign('tournament_id')
                  ->references('id')
                  ->on('tournaments')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rank');
    }
}
