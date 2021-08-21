<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class DeleteNotUsedAndAdminTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('admin_extension_histories');
        Schema::dropIfExists('admin_extensions');
        Schema::dropIfExists('admin_menu');
        Schema::dropIfExists('admin_permission_menu');
        Schema::dropIfExists('admin_permissions');
        Schema::dropIfExists('admin_role_menu');
        Schema::dropIfExists('admin_role_permissions');
        Schema::dropIfExists('admin_role_users');
        Schema::dropIfExists('admin_roles');
        Schema::dropIfExists('admin_settings');
        Schema::dropIfExists('admin_users');
        Schema::dropIfExists('game_contests');
        Schema::dropIfExists('game_contest_information');
        Schema::dropIfExists('ngproxy_application_user_traffics');
        Schema::dropIfExists('ngproxy_application_users');
        Schema::dropIfExists('ngproxy_applications');
        Schema::dropIfExists('ngproxy_custom_songs');
        Schema::dropIfExists('ngproxy_traffic_codes');
        Schema::dropIfExists('notices');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
