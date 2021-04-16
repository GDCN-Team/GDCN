<?php

/*
 * Copyright (c) 2020. WOSHIZHAZHA120 & GDCN Team
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateGameChallengesTable
 */
class CreateGameChallengesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('game_challenges', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('type');
            $table->string('name');
            $table->unsignedInteger('collect_count')->default(0);
            $table->unsignedInteger('reward_count')->default(0);
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
        Schema::dropIfExists('game_challenges');
    }
}
