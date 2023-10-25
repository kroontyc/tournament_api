<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('match', function (Blueprint $table) {
            $table->id();
            $table->string('first_fighter')->nullable();
            $table->string('second_fighter')->nullable();
            $table->string('result')->nullable(); 
            $table->string('stage')->nullable();
            $table->string('score')->nullable(); 
            $table->unsignedBigInteger('tournament_id');
            $table->foreign('tournament_id')->references('id')->on('tournaments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('match');
    }
}
