<?php

namespace App\Http\Controllers\Web\Tools;

use App\Exceptions\Web\Tools\AccountLinkException;
use App\Exceptions\Web\Tools\AccountUnLinkException;
use App\Exceptions\Web\Tools\LevelTransInException;
use App\Exceptions\Web\Tools\LevelTransOutException;
use App\Exceptions\Web\Tools\SongEditException;
use App\Exceptions\Web\Tools\SongUploadLinkException;
use App\Exceptions\Web\Tools\SongUploadNeteaseException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Tools\AccountLinkRequest;
use App\Http\Requests\Web\Tools\LevelTransInRequest;
use App\Http\Requests\Web\Tools\LevelTransOutRequest;
use App\Http\Requests\Web\Tools\SongEditRequest;
use App\Http\Requests\Web\Tools\SongUploadLinkRequest;
use App\Http\Requests\Web\Tools\SongUploadNeteaseRequest;
use App\Models\Game\Account\Link;
use App\Models\Game\CustomSong;
use App\Services\Web\ToolsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class ApiController extends Controller
{
    public function __construct(
        public ToolsService $service
    )
    {
    }

    /**
     * @param AccountLinkRequest $request
     * @return RedirectResponse
     */
    public function linkAccount(AccountLinkRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            if (!$this->service->account->link($data['server'], $data['name'], $data['password'])) {
                $this->service->notification->sendMessage('error', '链接失败');
                return back();
            }

            $this->service->notification->sendMessage('success', '链接成功!');
            return Redirect::route('tools.account.link.list');
        } catch (AccountLinkException $e) {
            $this->service->notification->sendMessage('error', $e->getMessage());
            return back();
        }
    }

    /**
     * @param Link $link
     * @return RedirectResponse
     */
    public function unlinkAccount(Link $link): RedirectResponse
    {
        try {
            if (!$this->service->account->unlink($link)) {
                $this->service->notification->sendMessage('error', '解绑失败');
                return back();
            }
        } catch (AccountUnLinkException $e) {
            $this->service->notification->sendMessage('error', $e->getMessage());
            return back();
        }

        $this->service->notification->sendMessage('success', '解绑成功!');
        return back();
    }

    /**
     * @param LevelTransInRequest $request
     * @return RedirectResponse
     */
    public function transInLevel(LevelTransInRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            if (!$levelID = $this->service->level->transIn($data['server'], $data['levelID'])) {
                $this->service->notification->sendMessage('error', '搬运失败');
                return back();
            }

            $this->service->notification->sendMessage('success', "搬运成功! 关卡ID: $levelID");
            return back();
        } catch (LevelTransInException $e) {
            $this->service->notification->sendMessage('error', $e->getMessage());
            return back();
        }
    }

    /**
     * @param LevelTransOutRequest $request
     * @return RedirectResponse
     */
    public function transOutLevel(LevelTransOutRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            if (!$levelID = $this->service->level->transOut($data['server'], $data['levelID'], $data['linkID'], $data['password'], $data['level_name'], $data['level_desc'], $data['level_song_type'], $data['level_song_id'], $data['level_unlisted'], $data['level_password'])) {
                $this->service->notification->sendMessage('error', '搬运失败');
                return back();
            }

            $this->service->notification->sendMessage('success', "搬运成功! 关卡ID: $levelID");
            return back();
        } catch (LevelTransOutException $e) {
            $this->service->notification->sendMessage('error', $e->getMessage());
            return back();
        }
    }

    /**
     * @param SongUploadLinkRequest $request
     * @return RedirectResponse
     */
    public function uploadSongUsingLink(SongUploadLinkRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            if (!$this->service->song->uploadLink($data['song_id'], $data['name'], $data['author_name'], $data['link'])) {
                $this->service->notification->sendMessage('error', '上传失败');
                return back();
            }

            $this->service->notification->sendMessage('success', "上传成功!");
            return Redirect::route('tools.song.list');
        } catch (SongUploadLinkException $e) {
            $this->service->notification->sendMessage('error', $e->getMessage());
            return back();
        }
    }

    /**
     * @param SongUploadNeteaseRequest $request
     * @return RedirectResponse
     */
    public function uploadSongUsingNeteaseMusic(SongUploadNeteaseRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            if (!$this->service->song->uploadNetease($data['song_id'], $data['musicID'])) {
                $this->service->notification->sendMessage('error', '上传失败');
                return back();
            }

            $this->service->notification->sendMessage('success', "上传成功!");
            return Redirect::route('tools.song.list');
        } catch (SongUploadNeteaseException $e) {
            $this->service->notification->sendMessage('error', $e->getMessage());
            return back();
        }
    }

    /**
     * @param CustomSong $song
     * @return RedirectResponse
     */
    public function deleteSong(CustomSong $song): RedirectResponse
    {
        if (!$this->service->song->delete($song)) {
            $this->service->notification->sendMessage('error', '删除失败');
            return back();
        }

        $this->service->notification->sendMessage('success', '删除成功!');
        return Redirect::route('tools.song.list');
    }

    /**
     * @param SongEditRequest $request
     * @return RedirectResponse
     */
    public function editSong(SongEditRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            if (!$this->service->song->edit($data['id'], $data['name'], $data['author_name'])) {
                $this->service->notification->sendMessage('error', '修改失败');
                return back();
            }

            $this->service->notification->sendMessage('success', '修改成功!');
            return Redirect::route('tools.song.list');
        } catch (SongEditException $e) {
            $this->service->notification->sendMessage('error', $e->getMessage());
            return back();
        }
    }
}
