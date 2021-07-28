<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\Traits\ResponseTrait;
use App\Models\Game\Account;
use App\Models\Game\Account\Comment as AccountComment;
use App\Models\Game\Level;
use App\Models\Game\Level\Comment as LevelComment;
use App\Models\Game\Level\Pack as LevelPack;
use App\Models\Notice;

class ServerController extends Controller
{
    use ResponseTrait;

    /**
     * @return array
     */
    public function getStat(): array
    {
        return $this->response(true, null, [
            'notice' => Notice::latest(),
            'count' => [
                'account' => Account::count(),
                'level' => Level::count(),
                'pack' => LevelPack::count(),
                'comment' => AccountComment::count() + LevelComment::count()
            ]
        ]);
    }
}
