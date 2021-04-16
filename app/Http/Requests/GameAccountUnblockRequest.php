<?php

namespace App\Http\Requests;

use App\Models\GameAccountBlock;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class GameAccountUnblockRequest
 * @package App\Http\Requests
 */
class GameAccountUnblockRequest extends GameAccountBlockRequest
{
    /**
     * @var GameAccountBlock
     */
    public $block;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        if (empty($this->accountID) || empty($this->targetAccountID)) {
            return false;
        }

        try {
            $this->block = GameAccountBlock::whereAccount($this->accountID)->whereTargetAccount($this->targetAccountID)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return false;
        }

        return parent::authorize();
    }
}
