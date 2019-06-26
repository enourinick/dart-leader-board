<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UserRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the post request.
     *
     * @return array
     */
    protected function postRules()
    {
        return [
            'email' => 'required|unique:users,email|email',
            'password' => 'required|string|min:6',
            'name' => 'required|string|between:1,255'
        ];
    }

    protected function putRules()
    {
        return [
            'email' => [
                'filled',
                Rule::unique('users')->ignore($this->user('api')->getKey(), 'id'),
                'email'
                ],
            'password' => 'filled|string|min:6',
            'name' => 'filled|string|between:1,255'
        ];
    }
}
