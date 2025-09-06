<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
        return [
            'paymethod' => ['required', 'in:card,convenience_store'],
        ];
    }
    public function messages()
    {
        return [
            'paymethod.required' => '支払い方法を選択してください',
            'paymethod.in' => '支払い方法を選択してください',
        ];
    }
}
