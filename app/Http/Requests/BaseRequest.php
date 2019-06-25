<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class BaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'GET':
            case 'DELETE':
                return [];
            case 'POST':
                return $this->postRules();
            case 'PUT':
            case 'PATCH':
                return $this->putRules();
        }
    }

    /**
     * Get the validation rules that apply to the post request.
     *
     * @return array
     */
    protected abstract function postRules();

    /**
     * Get the validation rules that apply to the put/patch request.
     *
     * @return array
     */
    protected function putRules()
    {
        return $this->postRules();
    }
}
