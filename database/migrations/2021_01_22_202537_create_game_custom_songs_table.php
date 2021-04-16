<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGameCustomSongsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('game_custom_songs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('song_id')->unique();
            $table->unsignedTinyInteger('type');
            $table->foreignId('uploader');
            $table->string('name');
            $table->string('author_name');
            $table->unsignedDecimal('size');
            $table->string('download_url');
            $table->string('hash');
            $table->boolean('disabled');
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
        Schema::dropIfExists('game_custom_songs');
    }
}
