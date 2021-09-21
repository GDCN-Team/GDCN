<?php

namespace App\Services\Web\Dashboard;

use App\Exceptions\Web\Dashboard\Contest\JoinException;
use App\Models\Game\Account;
use App\Models\Game\Level;
use App\Models\Game\Level\Contest;
use App\Services\Web\NotificationService;
use Illuminate\Support\Facades\Auth;

class ContestService
{
    public function __construct(
        public NotificationService $notification
    )
    {
    }

    /**
     * @throws JoinException
     */
    public function joinContest(Contest $contest, Level $level): Contest\Level
    {
        if (
            Contest\Level::where('contestID', $contest->id)
                ->whereHas('level', function ($query) {
                    /** @var Account $account */
                    $account = Auth::user();

                    $query->where('user', $account->user->id);
                })->exists()
        ) {
            throw new JoinException('您已经参加过该场比赛');
        }

        return Contest\Level::create([
            'contestID' => $contest->id,
            'levelID' => $level->id
        ]);
    }
}
