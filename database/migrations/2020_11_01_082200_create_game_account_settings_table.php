<?php

/*
 * Copyright (c) 2020. WOSHIZHAZHA120 & GDCN Team
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateGameAccountSettingsTable
 */
class CreateGameAccountSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('game_account_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account')->unique()->index();
            $table->unsignedTinyInteger('message_state')->default(0);
            $table->unsignedTinyInteger('friend_request_state')->default(0);
            $table->unsignedTinyInteger('comment_history_state')->default(0);
            $table->string('youtube')->nullable();
            $table->string('twitter')->nullable();
            $table->string('twitch')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('game_account_settings');
    }
}
