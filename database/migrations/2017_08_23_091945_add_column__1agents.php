<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumn1agents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->integer('gdv');
            $table->integer('pgdkd');
            $table->integer('tv');
            $table->integer('gsv');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->integer('gdv');
            $table->integer('pgdkd');
            $table->integer('tv');
            $table->integer('gsv');
        });
    }
}
