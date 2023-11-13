<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKeysMatches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {
        Schema::table('match', function (Blueprint $table) {
            $table->string('first_fighter_brand'); // Tipo 'string' é um exemplo, ajuste conforme a necessidade
            $table->string('second_fighter_brand'); // Nome adaptado sem acentuação
            $table->string('first_fighter_name');
             $table->string('first_fighter_categorie');
             $table->string('second_fighter_categorie');
            $table->string('second_fighter_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('match', function (Blueprint $table) {
            $table->dropColumn('first_fighter_brand'); // Tipo 'dropColumn' é um exemplo, ajuste conforme a necessidade
            $table->dropColumn('second_fighter_brand'); // Nome adaptado sem acentuação
            $table->dropColumn('first_fighter_name');
             $table->dropColumn('first_fighter_categorie');
             $table->dropColumn('second_fighter_categorie');
            $table->dropColumn('second_fighter_name');
        });
    }
}
