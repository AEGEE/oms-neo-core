<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/* Renames tables to be migrated to the new schema in a later migration. */
class CleanStart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      $tables = $this->getTables();
      foreach($tables as $table) {
        if (Schema::hasTable($table)) {
          Schema::rename($table, 'old_' . $table);
        }
      }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      $tables = $this->getTables();
      foreach($tables as $table) {
        if (Schema::hasTable('old_' . $table)) {
          Schema::rename('old_' . $table, $table);
        }
      }
    }

    private function getTables() {
      return array(
      'antennas',
      'auths',
      'board_members',
      'bodies',
      'body_memberships',
      'countries',
      'departments',
      'email_templates',
      'fee_users',
      'fees',
      'forums',
      'global_options',
      'menu_items',
      'module_pages',
      'modules',
      'news',
      'notifications',
      'organization_roles',
      'proxy_requests',
      'recruted_comments',
      'recruted_users',
      'recrutement_campaigns',
      'role_module_pages',
      'roles',
      'study_fields',
      'study_types',
      'types',
      'user_roles',
      'user_working_groups',
      'users',
      'working_groups',
      'seeder_logs',
      );
    }
}
