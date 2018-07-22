<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('year_order_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('shipping_address',800)->nullable();
            $table->decimal('shipping_charge', 5,2)->nullable();
            $table->dateTime('shipping_date');
            $table->decimal('total', 10,2)->nullable();
            $table->enum('payment_status', ['completed','incompleted'])->default('completed');
            $table->enum('delivery_status', ['pending','fullfilled','unfulfilled'])->default('pending');
            $table->enum('order_status', ['processed','completed','cancelled'])->default('processed');
            $table->string('additional_comments',800)->nullable();
            $table->timestamps();
        });

        Schema::create('order_detail', function(Blueprint $table){
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->integer('user_id')->unsigned()->nullable()->comment('lunch user who has completed his order');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('restaurant_id')->unsigned();
            $table->foreign('restaurant_id')->references('id')->on('restaurant')->onDelete('cascade');
            $table->integer('item_id')->unsigned();
            $table->foreign('item_id')->references('id')->on('dishes')->onDelete('cascade');
            $table->smallInteger('quantity')->unsigned();
            $table->integer('plate_id')->unsigned()->nullable()->comment('It may be id of Half, Full or Quarter from Plates table.');
            $table->foreign('plate_id')->references('id')->on('plates')->onDelete('set null');
            $table->string('email');
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
        Schema::dropIfExists('order_detail');
        Schema::dropIfExists('orders');
    }
}
