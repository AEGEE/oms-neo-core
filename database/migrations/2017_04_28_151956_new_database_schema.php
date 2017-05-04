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
            $table->string('name'); //TODO remove
            $table->string('code'); //TODO rename to key
            $table->string('value');
            $table->text('description')->nullable();
            $table->integer('not_editable')->nullable();
            $table->timestamps();
        });

        Schema::create('seeder_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code'); //TODO rename to key
            $table->timestamps();
        });

        Schema::create('countries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('country_id')->unsigned();
            $table->string('street');
            $table->string('zipcode');
            $table->string('city');
            $table->timestamps();

            $table->foreign('country_id')->references('id')->on('countries')->onDelete('restrict');
        });

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('address_id')->unsigned();
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
            $table->timestamp('activated_at')->nullable();
            $table->timestamps();

            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('restrict');
        });

        Schema::create('auths', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->string('ip_address', 45);
            $table->string('user_agent');
            $table->text('raw_request_params');
            $table->string('token_generated')->nullable();
            $table->integer('successful')->nullable();
            $table->timestamp('expiration')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('body_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('bodies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type_id')->unsigned();
            $table->integer('address_id')->unsigned();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('description')->nullable();
            $table->char('legacy_key', 3)->nullable();
            $table->timestamps();

            $table->foreign('type_id')->references('id')->on('body_types')->onDelete('restrict');
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('restrict');
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

        Schema::create('universities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('address_id')->unsigned();
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();

            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('restrict');
        });

        Schema::create('studies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('study_field_id')->unsigned();
            $table->integer('study_type_id')->unsigned();
            $table->integer('university_id')->unsigned();
            $table->integer('status');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('study_field_id')->references('id')->on('study_fields')->onDelete('restrict');
            $table->foreign('study_type_id')->references('id')->on('study_types')->onDelete('restrict');
            $table->foreign('university_id')->references('id')->on('study_types')->onDelete('restrict');
        });

        Schema::create('body_memberships', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('body_id')->unsigned();
            $table->integer('status')->nullable();
            $table->timestamp('start_date');
            $table->timestamp('end_date')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('body_id')->references('id')->on('bodies')->onDelete('cascade');
        });

        Schema::create('global_circles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('body_circles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('body_id')->unsigned();
            $table->integer('global_circle_id')->unsigned()->nullable();
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();

            $table->foreign('body_id')->references('id')->on('bodies')->onDelete('cascade');
            $table->foreign('global_circle_id')->references('id')->on('global_circles')->onDelete('restrict'); //Maybe simply unlink instead of restricting?
        });

        Schema::create('body_membership_circles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('membership_id')->unsigned();
            $table->integer('circle_id')->unsigned();
            $table->timestamps();

            $table->foreign('membership_id')->references('id')->on('body_memberships')->onDelete('cascade');
            $table->foreign('circle_id')->references('id')->on('body_circles')->onDelete('restrict');
        });



//Older tables, to be replaced later.

        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('code');
            $table->integer('system_role');
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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auths');

        Schema::dropIfExists('studies');
        Schema::dropIfExists('study_fields');
        Schema::dropIfExists('study_types');
        Schema::dropIfExists('universities');

        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('body_membership_circles');
        Schema::dropIfExists('body_memberships');

        Schema::dropIfExists('users');
        Schema::dropIfExists('body_circles');
        Schema::dropIfExists('global_circles');
        Schema::dropIfExists('bodies');

        Schema::dropIfExists('addresses');
        Schema::dropIfExists('body_types');
        Schema::dropIfExists('countries');
        Schema::dropIfExists('global_options');
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('role_module_pages');
        Schema::dropIfExists('module_pages');
        Schema::dropIfExists('modules');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('seeder_logs');
    }
}
