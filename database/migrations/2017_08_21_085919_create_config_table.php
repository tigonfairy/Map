<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('configs');
        Schema::create('configs', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('fontSize');
            $table->string('textColor');
            $table->string('background');
            $table->integer('position_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configs');
    }
}
