<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewDatabaseSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('global_options', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('code');
            $table->string('value');
            $table->text('description')->nullable();
            $table->integer('not_editable')->nullable();
            $table->timestamps();
        });

        Schema::create('seeder_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->timestamps();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->unique();
            $table->integer('user_id')->nullables();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('payload');
            $table->integer('last_activity');
        });

        Schema::create('countries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('study_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('study_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('body_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('bodies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type_id');
            $table->integer('address_id');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('description');
            $table->char('legacy_key', 3);
            $table->timestamps();

            $table->foreign('type_id')->references('id')->on('body_types')->onDelete('cascade');
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('cascade');
        });

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('address_id');
            $table->text('oauth_token')->nullable();
            $table->timestamp('oauth_expiration')->nullable();
            $table->string('contact_email');
            $table->string('first_name');
            $table->string('last_name');
            $table->timestamp('date_of_birth');
            $table->integer('gender');
            $table->string('phone')->nullable();
            $table->string('password');
            $table->integer('is_superadmin')->nullable();
            $table->integer('is_suspended')->nullable();
            $table->string('suspended_reason')->nullable();
            $table->string('seo_url')->nullable();
            $table->string('email_hash')->nullable();
            $table->timestamp('activated_at');
            $table->timestamps();

            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('cascade');
        });

        Schema::create('auths', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->string('ip_address', 45);
            $table->string('user_agent');
            $table->text('raw_request_params');
            $table->string('token_generated')->nullable();
            $table->integer('successful')->nullable();
            $table->timestamp('expiration')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('is_disabled')->nullable();
            $table->timestamps();
        });

        Schema::create('user_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id');
            $table->integer('user_id');
            $table->string('code')->nullable();
            $table->integer('system_role')->nullable();
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('modules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('code');
            $table->string('handshake_token');
            $table->string('base_url');
            $table->integer('is_active')->nullable();
            $table->timestamps();
        });

        Schema::create('module_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('module_id')->nullable();
            $table->string('name');
            $table->string('code');
            $table->string('module_link');
            $table->integer('is_active')->nullable();
            $table->string('icon')->nullable();
            $table->integer('is_hidden')->nullable();
            $table->timestamps();

            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
        });

        Schema::create('role_module_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('module_page_id');
            $table->integer('role_id');
            $table->integer('permission_level')->nullable();
            $table->timestamps();

            $table->foreign('module_page_id')->references('id')->on('module_pages')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });

        Schema::create('menu_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('type');
            $table->integer('weight');
            $table->integer('parent_id')->nullable();
            $table->integer('module_page_id')->nullable();
            $table->text('url')->nullable();
            $table->string('icon')->nullable();
            $table->timestamps();

            $table->foreign('module_page_id')->references('id')->on('module_pages')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('menu_items')->onDelete('cascade');
        });

        Schema::create('body_membership_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('body_id')->nullable();
            $table->integer('is_internal')->nullable();
            $table->integer('is_admin')->nullable();
            $table->timestamps();

            $table->foreign('body_id')->references('id')->on('bodies')->onDelete('cascade');
        });

        Schema::create('body_memberships', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('body_id');
            $table->integer('organizational_role_id')->nullable();
            $table->integer('status')->nullable();
            $table->timestamp('start_date');
            $table->timestamp('end_date')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('body_id')->references('id')->on('bodies')->onDelete('cascade');
            $table->foreign('organizational_role_id')->references('id')->on('organizational_roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sessions');
        Schema::drop('global_options');
        Schema::drop('countries');
        Schema::drop('study_fields');
        Schema::drop('study_types');
        Schema::drop('bodies');
        Schema::drop('users');
        Schema::drop('auths');
        Schema::drop('roles');
        Schema::drop('user_roles');
        Schema::drop('modules');
        Schema::drop('module_pages');
        Schema::drop('role_module_pages');
        schema::drop('body_types');
        schema::drop('body_membership_roles');
    }
}
