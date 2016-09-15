<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecrutedUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recruted_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('campaign_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->timestamp('date_of_birth');
            $table->integer('gender');
            $table->string('university');
            $table->integer('studies_type_id');
            $table->integer('studies_field_id');
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('zipcode')->nullable();
            $table->integer('status')->nullable();
            $table->integer('user_id_created')->nullable();
            $table->text('custom_responses')->nullable();
            $table->timestamps();

            $table->foreign('campaign_id')->references('id')->on('recrutement_campaigns')->onDelete('cascade');
            $table->foreign('studies_type_id')->references('id')->on('study_types')->onDelete('cascade');
            $table->foreign('studies_field_id')->references('id')->on('study_fields')->onDelete('cascade');
            $table->foreign('user_id_created')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('recruted_users');
    }
}
