<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImageDishRestaurantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dishes', function (Blueprint $table) {
            $table->string('image')->nullable();
        });
        Schema::table('dish_quantity', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->nullable();
        });
        Schema::table('restaurant', function (Blueprint $table) {
            $table->string('image')->nullable();
            $table->string('pincode')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dishes', function (Blueprint $table) {
            $table->dropColumn('image');
            
        });
        Schema::table('dish_quantity', function (Blueprint $table) {
            $table->dropColumn('price');
            
        });
        Schema::table('restaurant', function (Blueprint $table) {
            $table->dropColumn('image');
            $table->dropColumn('pincode');
            
        });
    }
}
