<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CleanStart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('antennas', 'old_antennas');
        Schema::rename('auths', 'old_auths');
        Schema::rename('board_members', 'old_board_members');
        Schema::rename('bodies', 'old_bodies');
        Schema::rename('body_memberships', 'old_body_memberships');
        Schema::rename('countries', 'old_countries');
        Schema::rename('departments', 'old_departments');
        Schema::rename('email_templates', 'old_email_templates');
        Schema::rename('fee_users', 'old_fee_users');
        Schema::rename('fees', 'old_fees');
        Schema::rename('forums', 'old_forums');
        Schema::rename('global_options', 'old_global_options');
        Schema::rename('menu_items', 'old_menu_items');
        Schema::rename('module_pages', 'old_module_pages');
        Schema::rename('modules', 'old_modules');
        Schema::rename('news', 'old_news');
        Schema::rename('notifications', 'old_notifications');
        Schema::rename('organization_roles', 'old_organization_roles');
        Schema::rename('proxy_requests', 'old_proxy_requests');
        Schema::rename('recruted_comments', 'old_recruted_comments');
        Schema::rename('recruted_users', 'old_recruted_users');
        Schema::rename('recrutement_campaigns', 'old_recrutement_campaigns');
        Schema::rename('role_module_pages', 'old_role_module_pages');
        Schema::rename('roles', 'old_roles');
        Schema::rename('study_fields', 'old_study_fields');
        Schema::rename('study_types', 'old_study_types');
        Schema::rename('types', 'old_types');
        Schema::rename('user_roles', 'old_user_roles');
        Schema::rename('user_working_groups', 'old_user_working_groups');
        Schema::rename('users', 'old_users');
        Schema::rename('working_groups', 'old_working_groups');
        Schema::rename('sessions', 'old_sessions');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('old_antennas', 'antennas');
        Schema::rename('old_auths', 'auths');
        Schema::rename('old_board_members', 'board_members');
        Schema::rename('old_bodies', 'bodies');
        Schema::rename('old_body_memberships', 'body_memberships');
        Schema::rename('old_countries', 'countries');
        Schema::rename('old_departments', 'departments');
        Schema::rename('old_email_templates', 'email_templates');
        Schema::rename('old_fee_users', 'fee_users');
        Schema::rename('old_fees', 'fees');
        Schema::rename('old_forums', 'forums');
        Schema::rename('old_global_options', 'global_options');
        Schema::rename('old_menu_items', 'menu_items');
        Schema::rename('old_module_pages', 'module_pages');
        Schema::rename('old_modules', 'modules');
        Schema::rename('old_news', 'news');
        Schema::rename('old_notifications', 'notifications');
        Schema::rename('old_organization_roles', 'organization_roles');
        Schema::rename('old_proxy_requests', 'proxy_requests');
        Schema::rename('old_recruted_comments', 'recruted_comments');
        Schema::rename('old_recruted_users', 'recruted_users');
        Schema::rename('old_recrutement_campaigns', 'recrutement_campaigns');
        Schema::rename('old_role_module_pages', 'role_module_pages');
        Schema::rename('old_roles', 'roles');
        Schema::rename('old_study_fields', 'study_fields');
        Schema::rename('old_study_types', 'study_types');
        Schema::rename('old_types', 'types');
        Schema::rename('old_user_roles', 'user_roles');
        Schema::rename('old_user_working_groups', 'user_working_groups');
        Schema::rename('old_users', 'users');
        Schema::rename('old_working_groups', 'working_groups');
        Schema::rename('old_sessions', 'sessions');
    }
}
