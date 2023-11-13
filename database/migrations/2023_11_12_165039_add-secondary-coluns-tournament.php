<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSecondaryColunsTournament extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {
        Schema::table('tournaments', function (Blueprint $table) {
            $table->string('local'); // Tipo 'string' é um exemplo, ajuste conforme a necessidade
            $table->string('federacao'); // Nome adaptado sem acentuação
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tournaments', function (Blueprint $table) {
            $table->dropColumn('local');
            $table->dropColumn('federacao');
        });
    }
}
