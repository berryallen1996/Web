<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_menu', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->bigInteger('parent')->index()->default(0);
            $table->enum('section', ['admin','sub-admin'])->default('admin');
            $table->bigInteger('created_by');
            $table->datetime('created_date');
            $table->enum('status',['active','inactive'])->default('active');
            $table->string('action_url', 255)->nullable();
            $table->string('menu_icon', 255)->nullable();
            $table->string('menu_class', 255)->nullable();
            $table->integer('menu_order');
            $table->string('callback', 255)->nullable();
            $table->tinyInteger('disable_list_view')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_menu');
    }
}
