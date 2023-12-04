<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('match', function (Blueprint $table) {
            $table->string('name'); // Tipo 'string' é um exemplo, ajuste conforme a necessidade
           
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
            $table->dropColumn('name');
           
        });
    }
}
