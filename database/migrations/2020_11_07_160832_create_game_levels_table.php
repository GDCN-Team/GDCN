<?php

/*
 * Copyright (c) 2020. WOSHIZHAZHA120 & GDCN Team
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateGameLevelsTable
 */
class CreateGameLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('game_levels', function (Blueprint $table) {
            $table->id()
                ->startingValue(71);

            $table->foreignId('user');
            $table->unsignedInteger('game_version');
            $table->string('name');
            $table->string('desc')->nullable();
            $table->integer('downloads')->default(0);
            $table->integer('likes')->default(0);
            $table->unsignedInteger('version')->default(0);
            $table->unsignedTinyInteger('length')->default(0);
            $table->unsignedInteger('audio_track')->default(0);
            $table->foreignId('song')->default(0);
            $table->boolean('auto')->default(false);
            $table->unsignedMediumInteger('password')->default(0);
            $table->foreignId('original')->default(0);
            $table->boolean('two_player')->default(false);
            $table->unsignedInteger('objects')->default(0);
            $table->unsignedTinyInteger('coins')->default(0);
            $table->unsignedTinyInteger('requested_stars')->default(0);
            $table->boolean('unlisted')->default(false);
            $table->boolean('ldm')->default(false);
            $table->mediumText('extra_string');
            $table->longText('level_info');
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
        Schema::dropIfExists('game_levels');
    }
}
