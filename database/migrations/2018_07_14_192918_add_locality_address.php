<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLocalityAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_addresses', function (Blueprint $table) {
            $table->string('pincode')->nullable();
            $table->integer('locality_id')->unsigned()->nullable();
            $table->foreign('locality_id')->references('id')->on('locality')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_addresses', function (Blueprint $table) {
            $table->dropForeign(['locality_id']);
            $table->dropColumn('locality_id');
            $table->dropColumn('pincode');
            
        });
    }
}
