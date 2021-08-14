<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CleanUnusedTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('gdproxy_ngproxy_binds');
        Schema::dropIfExists('gdproxy_replace_song_levels');
        Schema::dropIfExists('gdproxy_traffics');
        Schema::dropIfExists('ngproxy_application_user_traffics');
        Schema::dropIfExists('ngproxy_application_users');
        Schema::dropIfExists('ngproxy_applications');
        Schema::dropIfExists('ngproxy_custom_songs');
        Schema::dropIfExists('ngproxy_songs');
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
