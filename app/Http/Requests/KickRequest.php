<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class KickRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the post request.
     *
     * @return array
     */
    protected function postRules()
    {
        return [
            'user_id' => [
                'required',
                Rule::exists('game_user')->where(function ($query) {
                    $query->where('game_id', $this->game->id)->where('score', 0);
                }),
            ]
        ];
    }
}
