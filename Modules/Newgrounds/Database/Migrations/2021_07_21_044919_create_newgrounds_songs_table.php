<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewgroundsSongsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('newgrounds_songs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('author_id')->nullable();
            $table->string('author_name');
            $table->decimal('size');
            $table->string('youtube_video_id')->nullable();
            $table->string('author_youtube_url')->nullable();
            $table->string('download_url');
            $table->boolean('disabled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('newgrounds_songs');
    }
}
