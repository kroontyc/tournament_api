<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Results extends Migration
{
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->string('match_id')->nullable();
            $table->string('arena_id')->nullable();
            $table->string('winner')->nullable();
            $table->string('p1')->nullable();
            $table->string('p2')->nullable();
    
        });

      
    }
    
    public function down()
    {
        Schema::dropIfExists('participants');
    }
}
