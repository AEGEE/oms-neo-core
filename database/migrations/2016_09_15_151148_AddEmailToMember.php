<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmailToMember extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recruited_members', function (Blueprint $table) {
            $table->string('email');
            $table->string('email_hash');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recruited_members', function (Blueprint $table) {
            $table->dropColumn('email');
            $table->dropColumn('email_hash');
        });
    }
}
