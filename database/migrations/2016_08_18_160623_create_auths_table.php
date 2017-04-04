<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auths', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ip');
            $table->string('user_agent');
            $table->text('raw_request_params');
            $table->string('token_generated')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('successful')->nullable();
            $table->timestamp('expiration')->nullable();
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('user_models')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('auths');
    }
}
