<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGauntletIdToGameLevelGauntletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('game_level_gauntlets', function (Blueprint $table) {
            $table->unsignedTinyInteger('gauntlet_id')->nullable()->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('game_level_gauntlets', function (Blueprint $table) {
            $table->dropColumn('gauntlet_id');
        });
    }
}
