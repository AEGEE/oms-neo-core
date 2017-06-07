<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToMember extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->string('suspended_reason')->nullable();
            $table->string('seo_url')->nullable();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('activated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('suspended_reason');
            $table->dropColumn('seo_url');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('activated_at');
        });
    }
}
