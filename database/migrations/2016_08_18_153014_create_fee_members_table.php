<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeeMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fee_members', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fee_id');
            $table->integer('member_id');
            $table->timestamp('date_paid');
            $table->timestamp('expiration_date')->nullable();
            $table->timestamps();

            $table->foreign('fee_id')->references('id')->on('fees')->onDelete('cascade');
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('fee_members');
    }
}
