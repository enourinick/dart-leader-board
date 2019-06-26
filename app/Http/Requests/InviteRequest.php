<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class InviteRequest extends BaseRequest
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
                'exists:users,id',
                Rule::unique('game_user')->where(function ($query) {
                    return $query->where('game_id', $this->game->id);
                }),
            ]
        ];
    }
}
