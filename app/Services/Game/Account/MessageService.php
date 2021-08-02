<?php

namespace App\Services\Game\Account;

use App\Exceptions\Game\NoItemException;
use App\Exceptions\Game\NoPermissionException;
use App\Models\Game\Account;
use App\Models\Game\Account\Message;
use App\Repositories\Game\Account\MessageRepository;
use App\Services\Game\HelperService;
use GDCN\GDObject;

/**
 * Class MessageService
 * @package App\Services\Game\Account
 */
class MessageService
{

    /**
     * MessageService constructor.
     * @param MessageRepository $repository
     * @param HelperService $helper
     */
    public function __construct(
        protected MessageRepository $repository,
        protected HelperService $helper
    )
    {
    }

    /**
     * @param int|Account $account
     * @param int|Account $targetAccount
     * @param string $subject
     * @param string $body
     * @return null
     */
    public function upload(Account|int $account, Account|int $targetAccount, string $subject, string $body)
    {
        $account = $this->helper->getModel($account, Account::class);
        $targetAccount = $this->helper->getModel($targetAccount, Account::class);

        if ($account->can('send_message', $targetAccount)) {
            $message = new Message();
            $message->account = $account->id;
            $message->to_account = $targetAccount->id;
            $message->subject = $subject;
            $message->body = $body;
            return $message->save();
        }

        return null;
    }

    /**
     * @param int|Account $account
     * @param int $page
     * @param bool $getSent
     * @return string
     * @throws NoItemException
     */
    public function get(Account|int $account, int $page, bool $getSent): string
    {
        $messages = $this->repository->getByRelation($account, $getSent)
            ->forPage($page, $this->helper->perPage)
            ->get();

        $count = $messages->count();
        if ($count <= 0) {
            throw new NoItemException();
        }

        $this->helper->setCarbonLocaleToEnglish();
        return $messages->map(function (Message $message) use ($getSent) {
                $account = Account::whereId($getSent ? $message->to_account : $message->account)->firstOrFail();

                return GDObject::merge([
                    1 => $message->id,
                    2 => $account->id,
                    3 => $account->user->id,
                    4 => $message->subject,
                    6 => $account->name,
                    7 => $message->created_at->diffForHumans(null, true),
                    8 => $message->readed,
                    9 => $getSent
                ], ':');
            })->join('|') . '#' . $this->helper->generatePageHash($count, $page);
    }

    /**
     * @param int|Account $account
     * @param array $messages
     * @param bool $isSender
     * @return bool
     */
    public function multiDelete(Account|int $account, array $messages, bool $isSender): bool
    {
        foreach ($messages as $message) {
            $result = $this->singleDelete($account, $message, $isSender);
            if ($result === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param int|Account $account
     * @param int|Message $message
     * @param bool $isSender
     * @return bool
     */
    public function singleDelete(Account|int $account, int|Message $message, bool $isSender): bool
    {
        $account = $this->helper->getModel($account, Account::class);
        $message = $this->helper->getModel($message, Message::class);

        if ($account->can('delete', [$message, $isSender])) {
            $message->delete();
            return true;
        }

        return false;
    }

    /**
     * @param $account
     * @param $message
     * @param bool $isSender
     * @return string
     * @throws NoPermissionException
     */
    public function download($account, $message, bool $isSender): string
    {
        $account = $this->helper->getModel($account, Account::class);
        $message = $this->helper->getmodel($message, Message::class);

        if ($account->can('download', [$message, $isSender])) {
            $message->readed = true;
            $message->save();

            return GDObject::merge([
                1 => $message->id,
                2 => $isSender ? $message->to_account : $message->account,
                3 => $account->user->id,
                4 => $message->subject,
                5 => $message->body,
                6 => $account->name,
                7 => $message->created_at->diffForHumans(null, true),
                8 => $message->readed,
                9 => $isSender
            ], ':');
        }

        throw new NoPermissionException();
    }
}
