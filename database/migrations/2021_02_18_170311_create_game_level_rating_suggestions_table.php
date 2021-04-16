<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGameLevelRatingSuggestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('game_level_rating_suggestions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user');
            $table->foreignId('level');
            $table->unsignedTinyInteger('type');
            $table->unsignedTinyInteger('rating');
            $table->boolean('featured')->default(false);
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
        Schema::dropIfExists('game_level_rating_suggestions');
    }
}
