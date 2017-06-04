<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_agents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('agent_id');
            $table->integer('product_id');
            $table->text('month');
            $table->integer('sales_plan');
            $table->integer('sales_real');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_agents');
    }
}
