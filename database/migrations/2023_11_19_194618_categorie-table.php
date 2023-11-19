<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CategorieTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          Schema::table('categories', function (Blueprint $table) {
            $table->string('name'); // Tipo 'string' é um exemplo, ajuste conforme a necessidade
            $table->string('status'); // Nome adaptado sem acentuação
            $table->string('owner_id');
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
         Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('status');
              $table->dropColumn('owner_id');
        });
    }
}
