<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateGameAccountLinksTable
 */
class CreateGameAccountLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('game_account_links', function (Blueprint $table) {
            $table->id();
            $table->string('host');
            $table->foreignId('account');
            $table->foreignId('target_account_id');
            $table->foreignId('target_user_id');
            $table->string('target_name');
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
        Schema::dropIfExists('game_account_links');
    }
}
