<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnMatches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('match', function (Blueprint $table) {
            $table->string('arena_id')->nullable();
              $table->string('arena_name')->nullable();
  
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
         Schema::table('match', function (Blueprint $table) {
            $table->dropColumn('arena_id');
             $table->dropColumn('arena_name');
       
        });
    }
}
