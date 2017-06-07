<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecruitmentCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recruitment_campaigns', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('body_id');
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->string('link');
            $table->text('custom_fields')->nullable();
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
        Schema::drop('recruitment_campaigns');
    }
}
