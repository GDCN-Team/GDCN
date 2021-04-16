<?php

use App\Models\GameAccount;
use App\Models\GameAccountPermissionGroup;
use App\Models\GameContest;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('game:permission.group.create {name} {mod_level} {comment_color=255,255,255}', function($name, $mod_level, $comment_color) {
    $groupID = GameAccountPermissionGroup::query()
        ->insertGetId([
            'name' => $name,
            'mod_level' => $mod_level,
            'comment_color' => $comment_color
        ]);

    $this->info('Created, id: '.$groupID);
});

Artisan::command('game:contest.create {name} {desc} {owner} {expired_at}', function ($name, $desc, $owner, $expired_at) {
    $ownerAccountID = GameAccount::whereName($owner)->value('id');

    $time = strtotime($expired_at);
    $expired_at = date('Y-m-d G:i:s', $time);

    $contest = new GameContest();
    $contest->name = $name;
    $contest->desc = $desc;
    $contest->expired_at = $expired_at;
    $contest->owner = $ownerAccountID;
    $contest->save();

    $this->info('Created, id: ' . $contest->id);
});
