<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeRecrutementCampaigns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recrutement_campaigns', function (Blueprint $table) {
            $table->dropForegn("recrutement_campaigns_antenna_id_foreign");
            
            $table->renameColumn('antenna_id', 'body_id');

            $table->integer('requires_validation')->default(1);
            $table->integer('is_public')->default(1);

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
        Schema::table('recrutement_campaigns', function (Blueprint $table) {
            $table->dropForegn("recrutement_campaigns_antenna_id_foreign");

            $table->renameColumn('body_id', 'antenna_id');

            $table->dropColumn('requires_validation');
            $table->dropColumn('is_public');

            $table->foreign('antenna_id')->references('id')->on('antennas')->onDelete('cascade');
        });
    }
}
