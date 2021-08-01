<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNgproxyApplicationUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ngproxy_application_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('app_id');
            $table->foreignId('user_id');
            $table->ipAddress('bind_ip');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ngproxy_application_users');
    }
}
