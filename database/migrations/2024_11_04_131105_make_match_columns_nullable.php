<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeMatchColumnsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('match', function (Blueprint $table) {
            $table->string('first_fighter_brand')->nullable()->change();
            $table->string('second_fighter_brand')->nullable()->change();
            $table->string('first_fighter_name')->nullable()->change();
            $table->string('first_fighter_categorie')->nullable()->change();
            $table->string('second_fighter_categorie')->nullable()->change();
            $table->string('second_fighter_name')->nullable()->change();
            $table->string('name')->nullable()->change();
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
            $table->string('first_fighter_brand')->nullable(false)->change();
            $table->string('second_fighter_brand')->nullable(false)->change();
            $table->string('first_fighter_name')->nullable(false)->change();
            $table->string('first_fighter_categorie')->nullable(false)->change();
            $table->string('second_fighter_categorie')->nullable(false)->change();
            $table->string('second_fighter_name')->nullable(false)->change();
        });
    }
}
