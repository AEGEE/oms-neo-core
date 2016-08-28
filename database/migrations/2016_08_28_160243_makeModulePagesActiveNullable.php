<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeModulePagesActiveNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('module_pages', function (Blueprint $table) {
            $table->integer('is_active')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('module_pages', function (Blueprint $table) {
            $table->integer('is_active')->nullable(false)->change();
        });
    }
}
