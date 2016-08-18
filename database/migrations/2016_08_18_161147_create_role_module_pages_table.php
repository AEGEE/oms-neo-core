<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleModulePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_module_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('module_page_id');
            $table->integer('role_id');
            $table->integer('permission_level')->nullable();
            $table->timestamps();

            $table->foreign('module_page_id')->references('id')->on('module_pages')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('role_module_pages');
    }
}
