<?php

namespace App\Http\Controllers\Game;

use App\Enums\Game\ResponseCode;
use App\Game\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\GameAccountMessageDeleteRequest;
use App\Http\Requests\GameAccountMessageDownloadRequest;
use App\Http\Requests\GameAccountMessageGetRequest;
use App\Http\Requests\GameAccountMessageSendRequest;
use App\Models\GameAccount;
use App\Models\GameAccountMessage;
use Exception;
use GDCN\GDObject;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Carbon;

/**
 * Class AccountMessageController
 * @package App\Http\Controllers
 */
class AccountMessagesController extends Controller
{
    /**
     * @param GameAccountMessageSendRequest $request
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/uploadGJMessage20
     */
    public function send(GameAccountMessageSendRequest $request): int
    {
        $data = $request->validated();

        try {
            /** @var GameAccount $receiver */
            $receiver = GameAccount::whereId($data['toAccountID'])->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return ResponseCode::ACCOUNT_NOT_FOUND;
        }

        try {
            $this->authorize('send_message', $receiver);
        } catch (AuthorizationException $e) {
            return ResponseCode::PERMISSION_DENIED;
        }

        $message = GameAccountMessage::query()
            ->create([
                'account' => $data['accountID'],
                'to_account' => $data['toAccountID'],
                'subject' => $data['subject'],
                'body' => $data['body']
            ]);

        return $message->id ?? ResponseCode::FAILED;
    }

    /**
     * @param GameAccountMessageGetRequest $request
     * @param Helpers $helper
     * @return int|string
     *
     * @see http://docs.gdprogra.me/#/endpoints/getGJMessages20
     */
    public function get(GameAccountMessageGetRequest $request, Helpers $helper)
    {
        Carbon::setLocale('en');
        $data = $request->validated();

        if ($data['getSent'] ?? false) {
            $query = GameAccountMessage::whereAccount($data['accountID']);
        } else {
            $query = GameAccountMessage::whereToAccount($data['accountID']);
        }

        if ($query->count() <= 0) {
            return ResponseCode::EMPTY_RESULT;
        }

        $page = $data['page'];
        $messages = $query->forPage(++$page, $helper->perPage)
            ->get()
            ->map(function (GameAccountMessage $message) use ($data) {
                $accountID = ($data['getSent'] ?? false) ? $message->to_account : $message->account;
                $account = GameAccount::whereId($accountID)->firstOrFail();

                return GDObject::merge([
                    1 => $message->id,
                    2 => $account->id,
                    3 => $account->user->id,
                    4 => $message->subject,
                    6 => $account->name,
                    7 => $message->created_at->diffForHumans(null, true),
                    8 => $message->readed,
                    9 => $data['getSent'] ?? false
                ], ':');
            })->toArray();

        $result = implode('|', $messages);
        return "{$result}#{$helper->generatePageHash($query->count(), $page)}";
    }

    /**
     * @param GameAccountMessageDeleteRequest $request
     * @return int
     *
     * @see http://docs.gdprogra.me/#/endpoints/deleteGJMessages20
     */
    public function delete(GameAccountMessageDeleteRequest $request): int
    {
        foreach ($request->messages as $message) {
            try {
                $this->authorize('delete', $message);
                $message->delete();
            } catch (AuthorizationException $e) {
                return ResponseCode::PERMISSION_DENIED;
            } catch (Exception $e) {
                return ResponseCode::DELETE_FAILED;
            }

        }

        return ResponseCode::OK;
    }

    /**
     * @param GameAccountMessageDownloadRequest $request
     * @return int|string
     *
     * @see http://docs.gdprogra.me/#/endpoints/downloadGJMessageo20
     */
    public function download(GameAccountMessageDownloadRequest $request)
    {
        $data = $request->validated();

        try {
            $this->authorize('download', $request->message);
        } catch (AuthorizationException $e) {
            return ResponseCode::PERMISSION_DENIED;
        }

        try {
            $accountID = ($data['isSender'] ?? false) ? $request->message->to_account : $request->message->account;
            $account = GameAccount::whereId($accountID)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return ResponseCode::ACCOUNT_NOT_FOUND;
        }

        $request->message->readed = true;
        $request->message->save();

        Carbon::setLocale('en');
        return GDObject::merge([
            1 => $request->message->id,
            2 => $account->id,
            3 => $account->user->id,
            4 => $request->message->subject,
            5 => $request->message->body,
            6 => $account->name,
            7 => $request->message->created_at->diffForHumans(null, true),
            8 => $request->message->readed,
            9 => $data['isSender'] ?? false
        ], ':');
    }
}
