<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGdproxyNgproxyBindsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gdproxy_ngproxy_binds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id');
            $table->foreignId('ngproxy_user_id');
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
        Schema::dropIfExists('gdproxy_ngproxy_binds');
    }
}
