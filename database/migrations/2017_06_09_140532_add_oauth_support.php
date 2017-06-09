<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOauthSupport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('email_hash');
            $table->renameColumn('contact_email', 'personal_email');
            $table->string('internal_email')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email_hash')->nullable();
            $table->renameColumn('personal_email', 'contact_email');
            $table->dropColumn('internal_email');
        });
    }
}
