<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProxyRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proxy_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('from_module_id');
            $table->integer('to_module_id');
            $table->string('to_url');
            $table->text('request');
            $table->text('response');
            $table->timestamps();

            $table->foreign('from_module_id')->references('id')->on('modules')->onDelete('cascade');
            $table->foreign('to_module_id')->references('id')->on('modules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('proxy_requests');
    }
}
