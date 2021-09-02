<?php

namespace App\Services\Game\Account;

use App\Exceptions\Game\NoItemException;
use App\Models\Game\Account;
use App\Models\Game\Account\Message;
use GDCN\GDObject;
use GDCN\Hash\Components\PageInfo as PageInfoComponent;
use Illuminate\Support\Facades\Log;

class MessageService
{
    public function upload(int $accountID, int $targetAccountID, string $subject, string $body): ?Message
    {
        $account = Account::findOrFail($accountID);
        $targetAccount = Account::findOrFail($accountID);

        if ($account->cant('sendMessage', $targetAccount)) {
            Log::channel('gdcn')
                ->notice('[Account Message System] Action: Upload Message Failed', [
                    'accountID' => $accountID,
                    'targetAccountID' => $targetAccountID,
                    'subject' => $subject,
                    'body' => $body,
                    'reason' => 'Blocked By Account Setting Policy'
                ]);

            return null;
        }

        Log::channel('gdcn')
            ->info('[Account Message System] Action: Upload Message', [
                'accountID' => $accountID,
                'targetAccountID' => $targetAccountID,
                'subject' => $subject,
                'body' => $body
            ]);

        return $account->sent_messages()
            ->create([
                'to_account' => $targetAccountID,
                'subject' => $subject,
                'body' => $body
            ]);
    }

    /**
     * @throws NoItemException
     */
    public function get(int $accountID, bool $getSent, int $page): string
    {
        $account = Account::findOrFail($accountID);
        if ($getSent === true) {
            $account->loadCount('sent_messages');
            $messages = $account->sent_messages();
            $column = 'to_account';
            $count = $account->sent_messages_count;
        } else {
            $account->loadCount('messages');
            $messages = $account->messages();
            $column = 'account';
            $count = $account->messages_count;
        }

        if ($count <= 0) {
            throw new NoItemException();
        }

        $result = $messages->forPage(++$page, PageInfoComponent::$per_page)
            ->get()
            ->map(function (Message $message) use ($column, $getSent) {
                /** @var Account $account */
                $account = $message->getRelationValue($column);

                return GDObject::merge([
                    1 => $message->id,
                    2 => $account->id,
                    3 => $account->user->id,
                    4 => $message->getRawOriginal('subject'),
                    6 => $account->name,
                    7 => $message->created_at->locale('en')->diffForHumans(null, true),
                    8 => $message->readed,
                    9 => $getSent
                ], ':');
            })->join('|');

        Log::channel('gdcn')
            ->info('[Account Message System] Action: Get Messages', [
                'accountID' => $accountID,
                'getSent' => $getSent,
                'page' => $page
            ]);

        return implode('#', [
            $result,
            app(PageInfoComponent::class)->generate($count, $page)
        ]);
    }

    public function delete(int $accountID, array $messageIDs, bool $isSender): bool
    {
        $account = Account::findOrFail($accountID);
        $query = $isSender === true ? $account->sent_messages() : $account->messages();

        foreach ($messageIDs as $messageID) {
            Log::channel('gdcn')
                ->info('[Account Message System] Action: Delete Message', [
                    'accountID' => $accountID,
                    'messageID' => $messageID,
                    'isSender' => $isSender,
                    'result' => $query->whereKey($messageID)->delete()
                ]);
        }

        return true;
    }

    public function download(int $accountID, int $messageID, bool $isSender): string
    {
        $account = Account::findOrFail($accountID);

        /** @var Message $message */
        if ($isSender === true) {
            $column = 'to_account';
            $message = $account->sent_messages()
                ->whereKey($messageID)
                ->firstOrFail();
        } else {
            $column = 'account';
            $message = $account->messages()
                ->whereKey($messageID)
                ->firstOrFail();
        }

        $message->update([
            'readed' => true
        ]);

        Log::channel('gdcn')
            ->info('[Account Message System] Action: Download Message', [
                'accountID' => $accountID,
                'messageID' => $messageID,
                'isSender' => $isSender
            ]);

        return GDObject::merge([
            1 => $message->id,
            2 => $message->{$column},
            3 => $account->user->id,
            4 => $message->getRawOriginal('subject'),
            5 => $message->getRawOriginal('body'),
            6 => $account->name,
            7 => $message->created_at->locale('en')->diffForHumans(null, true),
            8 => $message->readed,
            9 => $isSender
        ], ':');
    }
}
