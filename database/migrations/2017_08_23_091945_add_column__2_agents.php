<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumn2agents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->dropColumn('gdv');
            $table->dropColumn('pgdkd');
            $table->dropColumn('tv');
            $table->dropColumn('gsv');
            $table->integer('gdv')->default(0);
            $table->integer('pgdkd')->default(0);
            $table->integer('tv')->default(0);
            $table->integer('gsv')->default(0);
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
            $table->dropColumn('gdv');
            $table->dropColumn('pgdkd');
            $table->dropColumn('tv');
            $table->dropColumn('gsv');
        });
    }
}
