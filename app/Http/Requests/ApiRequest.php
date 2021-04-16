<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ApiRequest
 * @package App\Http\Requests
 */
class ApiRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    /**
     * @param string|null $msg
     * @return array
     */
    public function failed(?string $msg = null): array
    {
        return [
            'status' => false,
            'msg' => $msg
        ];
    }

    /**
     * @param $data
     * @param string|null $msg
     * @return array
     */
    public function success($data = null, ?string $msg = null): array
    {
        return [
            'status' => true,
            'msg' => $msg,
            'data' => $data
        ];
    }
}
