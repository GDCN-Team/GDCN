<?php

/*
 * Copyright (c) 2020. WOSHIZHAZHA120 & GDCN Team
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateGameLevelGauntletsTable
 */
class CreateGameLevelGauntletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('game_level_gauntlets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('level1');
            $table->foreignId('level2');
            $table->foreignId('level3');
            $table->foreignId('level4');
            $table->foreignId('level5');
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
        Schema::dropIfExists('game_level_gauntlets');
    }
}
