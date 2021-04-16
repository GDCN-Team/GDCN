<?php

/*
 * Copyright (c) 2020. WOSHIZHAZHA120 & GDCN Team
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateGameLevelRatingsTable
 */
class CreateGameLevelRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('game_level_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('level')->index();
            $table->unsignedInteger('stars');
            $table->unsignedInteger('difficulty')->nullable();
            $table->unsignedInteger('featured_score');
            $table->boolean('epic');
            $table->boolean('coin_verified');
            $table->boolean('auto')->nullable();
            $table->boolean('demon');
            $table->unsignedTinyInteger('demon_difficulty')->nullable();
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
        Schema::dropIfExists('game_level_ratings');
    }
}
