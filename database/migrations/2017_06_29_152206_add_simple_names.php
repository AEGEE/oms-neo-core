<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSimpleNames extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name_simple');
            $table->string('last_name_simple');
        });

        Schema::table('bodies', function (Blueprint $table) {
            $table->string('name_simple');
        });

        Schema::table('circles', function (Blueprint $table) {
            $table->string('name_simple')->nullable();
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
            $table->dropColumn('first_name_simple');
            $table->dropColumn('last_name_simple');
        });

        Schema::table('bodies', function (Blueprint $table) {
            $table->dropColumn('name_simple');
        });

        Schema::table('circles', function (Blueprint $table) {
            $table->dropColumn('name_simple');
        });
    }
}
