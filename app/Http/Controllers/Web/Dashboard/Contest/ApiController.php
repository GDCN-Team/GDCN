<?php

namespace App\Http\Controllers\Web\Dashboard\Contest;

use App\Exceptions\Web\Dashboard\Contest\JoinException;
use App\Http\Controllers\Controller;
use App\Models\Game\Level;
use App\Models\Game\Level\Contest;
use App\Services\Web\Dashboard\ContestService;
use Illuminate\Http\RedirectResponse;

class ApiController extends Controller
{
    public function __construct(
        public ContestService $service
    )
    {
    }

    public function joinContest(Contest $contest, Level $level): RedirectResponse
    {
        try {
            if ($level = $this->service->joinContest($contest, $level)) {
                $this->service->notification->sendMessage('success', '参加成功!');
            } else {
                $this->service->notification->sendMessage('error', '参加失败');
            }
        } catch (JoinException $e) {
            $this->service->notification->sendMessage('error', '参加失败, 原因: ' . $e->getMessage());
        }

        return back();
    }
}
