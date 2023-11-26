<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKeysArena extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('arenas', function (Blueprint $table) {
            $table->string('first_fighter_name')->nullable();
              $table->string('second_fighter_name')->nullable();
  
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
         Schema::table('arenas', function (Blueprint $table) {
            $table->dropColumn('first_fighter_name');
             $table->dropColumn('second_fighter_name');
       
        });
    }
}
