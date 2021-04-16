<?php

/*
 * Copyright (c) 2020. WOSHIZHAZHA120 & GDCN Team
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateGameLevelPacksTable
 */
class CreateGameLevelPacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('game_level_packs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('levels');
            $table->integer('stars');
            $table->unsignedTinyInteger('coins');
            $table->unsignedTinyInteger('difficulty');
            $table->string('text_color');
            $table->string('bar_color')->nullable();
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
        Schema::dropIfExists('game_level_packs');
    }
}
