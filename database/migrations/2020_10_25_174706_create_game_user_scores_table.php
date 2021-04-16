<?php

/*
 * Copyright (c) 2020. WOSHIZHAZHA120 & GDCN Team
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateGameUserScoresTable
 */
class CreateGameUserScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('game_user_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user')->unique()->index();
            $table->integer('game_version');
            $table->integer('binary_version');
            $table->integer('stars')->default(0);
            $table->integer('demons')->default(0);
            $table->integer('diamonds')->default(0);
            $table->integer('icon')->default(0);
            $table->integer('color1')->default(0);
            $table->integer('color2')->default(3);
            $table->integer('icon_type')->default(0);
            $table->integer('coins')->default(0);
            $table->integer('user_coins')->default(0);
            $table->integer('special')->default(0);
            $table->integer('acc_icon')->default(0);
            $table->integer('acc_ship')->default(0);
            $table->integer('acc_ball')->default(0);
            $table->integer('acc_bird')->default(0);
            $table->integer('acc_dart')->default(0);
            $table->integer('acc_robot')->default(0);
            $table->integer('acc_glow')->default(0);
            $table->integer('acc_spider')->default(0);
            $table->integer('acc_explosion')->default(0);
            $table->integer('creator_points')->default(0);
            $table->integer('chest1count')->default(0);
            $table->timestamp('chest1time')->nullable();
            $table->integer('chest2count')->default(0);
            $table->timestamp('chest2time')->nullable();
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
        Schema::dropIfExists('game_user_scores');
    }
}
