<?php

/*
 * Copyright (c) 2020. WOSHIZHAZHA120 & GDCN Team
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateGameLevelScoresTable
 */
class CreateGameLevelScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('game_level_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account');
            $table->foreignId('level');
            $table->unsignedTinyInteger('percent');
            $table->integer('attempts');
            $table->unsignedTinyInteger('coins');
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
        Schema::dropIfExists('game_level_scores');
    }
}
