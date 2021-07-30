<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNgproxyCustomSongsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ngproxy_custom_songs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('song_id');
            $table->string('name');
            $table->string('author_name');
            $table->decimal('size');
            $table->string('download_link');
            $table->boolean('disabled')->default(false);
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
        Schema::dropIfExists('ngproxy_custom_songs');
    }
}
