<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmailToUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recruted_users', function (Blueprint $table) {
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
        Schema::table('recruted_users', function (Blueprint $table) {
            $table->dropColumn('email');
            $table->dropColumn('email_hash');
        });
    }
}
