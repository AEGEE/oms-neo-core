<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('body_id')->nullable();
            $table->integer('is_internal')->nullable();
            $table->integer('is_admin')->nullable();
            $table->timestamps();

            $table->foreign('body_id')->references('id')->on('bodies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('organization_roles');
    }
}
