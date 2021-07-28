<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNgproxySongsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ngproxy_songs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('author_id')->nullable();
            $table->string('author_name');
            $table->decimal('size');
            $table->string('video_id')->nullable();
            $table->string('author_youtube_url')->nullable();
            $table->string('download_link');
            $table->boolean('disabled')->default(false);
            $table->unsignedBigInteger('level_count')->default(0);
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
        Schema::dropIfExists('ngproxy_songs');
    }
}
