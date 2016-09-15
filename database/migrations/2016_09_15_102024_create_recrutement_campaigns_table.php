<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecrutementCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recrutement_campaigns', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('antenna_id');
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->string('link');
            $table->text('custom_fields')->nullable();
            $table->timestamps();

            $table->foreign('antenna_id')->references('id')->on('antennas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('recrutement_campaigns');
    }
}
