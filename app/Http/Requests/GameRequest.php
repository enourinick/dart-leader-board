<?php

namespace App\Http\Requests;

class GameRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the post request.
     *
     * @return array
     */
    protected function postRules()
    {
        return [
            'target_score' => 'required|integer|min:1'
        ];
    }
}
