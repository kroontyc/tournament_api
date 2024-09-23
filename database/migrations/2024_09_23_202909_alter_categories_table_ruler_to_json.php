<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCategoriesTableRulerToJson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            // Alterar a coluna 'ruler' para JSON
            $table->json('ruler')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            // Reverter para o tipo anterior, ajustando conforme o tipo original da coluna
            $table->string('ruler')->change(); // Altere para o tipo original caso diferente
        });
    }
}
