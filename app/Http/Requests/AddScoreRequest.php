<?php

namespace App\Http\Requests;

class AddScoreRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the post request.
     *
     * @return array
     */
    protected function postRules()
    {
        return [
            'score' => 'required|integer|min:0'
        ];
    }
}
