<?php

namespace App\Rules;

use App\Models\Game\Account;
use GDCN\Hash\Components\GJP as GJPComponent;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

class ValidateAccountCreditRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        switch ($attribute) {
            case 'password':
                $userName = Request::get('userName');
                $account = Account::whereName($userName)->first();
                return Hash::check($value, $account->password);
            case 'gjp':
                $accountID = Request::get('accountID');
                $account = Account::find($accountID);
                $password = app(GJPComponent::class)->decode($value);
                return Hash::check($password, $account->password);
            default:
                return false;
        }
    }

    public function message(): string
    {
        return ':attribute validate failed.';
    }
}
