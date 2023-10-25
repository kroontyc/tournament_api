<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Participants extends Migration
{
    public function up()
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tournament_id');
            $table->string('name');
            $table->integer('age');
            $table->string('weight');
            $table->string('rank');
            $table->string('height'); 
            $table->string('gub'); 
            $table->string('sex'); 
            $table->string('team_name'); 
            $table->string('categorie'); 
            $table->foreign('tournament_id')->references('id')->on('tournaments');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('participants');
    }
}
