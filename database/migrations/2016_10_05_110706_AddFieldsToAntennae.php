<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToAntennae extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('antennas', function (Blueprint $table) {
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('antennas', function (Blueprint $table) {
            $table->dropColumn('email');
            $table->dropColumn('address');
            $table->dropColumn('phone');
        });
    }
}
