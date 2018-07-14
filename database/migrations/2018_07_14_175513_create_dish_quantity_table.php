<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDishQuantityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dish_quantity', function (Blueprint $table) {
            $table->integer('dish_id')->unsigned();
            $table->foreign('dish_id')->references('id')->on('dishes')->onDelete('cascade');
            $table->integer('quantity_id')->unsigned();
            $table->foreign('quantity_id')->references('id')->on('dishes_quantity')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dish_quantity');
    }
}
