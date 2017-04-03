<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->increments('id');
            $table->string('internal_email')->nullable();
            $table->string('oauth_token')->nullable();
            $table->timestamp('oauth_expiration')->nullable();
            $table->string('contact_email');
            $table->string('first_name');
            $table->string('last_name');
            $table->timestamp('date_of_birth');
            $table->integer('gender');
            $table->integer('body_id');
            $table->string('university');
            $table->integer('studies_type_id');
            $table->integer('studies_field_id');
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('zipcode')->nullable();
            $table->text('other')->nullable();
            $table->integer('is_superadmin')->nullable();
            $table->integer('is_suspended')->nullable();
            $table->string('password')->nullable();
            $table->integer('department_id')->nullable();
            $table->timestamps();

            $table->foreign('body_id')->references('id')->on('bodies')->onDelete('cascade');
            $table->foreign('studies_type_id')->references('id')->on('study_types')->onDelete('cascade');
            $table->foreign('studies_field_id')->references('id')->on('study_fields')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('members');
    }
}
