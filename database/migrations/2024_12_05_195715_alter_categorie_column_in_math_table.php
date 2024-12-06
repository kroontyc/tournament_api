<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCategorieColumnInMathTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('math', function (Blueprint $table) {
            $table->longText('categorie')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('math', function (Blueprint $table) {
            $table->string('categorie', 255)->change(); // Reverte para string com limite de 255 caracteres
        });
    }
}
