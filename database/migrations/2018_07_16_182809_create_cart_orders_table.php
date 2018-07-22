<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_order', function (Blueprint $table) {
            $table->dateTime('shipping_date');
            $table->integer('user_id')->unsigned()->nullable()->comment('lunch user who has completed his order');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('restaurant_id')->unsigned()->nullable();
            $table->foreign('restaurant_id')->references('id')->on('restaurant')->onDelete('set null');
            $table->integer('item_id')->unsigned();
            $table->foreign('item_id')->references('id')->on('dishes')->onDelete('cascade');
            $table->smallInteger('quantity')->unsigned()->nullable();
            $table->integer('plate_id')->unsigned()->nullable()->comment('It may be id of Half, Full or Quarter from Plates table.');
            $table->foreign('plate_id')->references('id')->on('plates')->onDelete('set null');
            $table->decimal('price',10,2);
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
        Schema::dropIfExists('cart_order');
    }
}
