<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKeysResultsMatch extends Migration
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
            $table->string('p1_score')->nullable(); // Tipo 'string' é um exemplo, ajuste conforme a necessidade
            $table->string('p2_score')->nullable();
              $table->string('accStage')->nullable();
                $table->string('winner_team')->nullable();
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
            $table->dropColumn('p1_score');
            $table->dropColumn('p2_score');
                $table->dropColumn('accStage');
                $table->dropColumn('winner_team');
        });
    }
}
