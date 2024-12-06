<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeJsonMatchColumnToJsonInMatchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('match', function (Blueprint $table) {
            // Alterar a coluna 'json_match' para JSON
            $table->json('json_match')->change();
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
            // Reverter a coluna 'json_match' para TEXT ou o tipo anterior
            $table->text('json_match')->change();
        });
    }
}
