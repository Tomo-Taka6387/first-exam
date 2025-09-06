<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendingAddressRequest extends FormRequest
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
            'sending_postcode' => ['required', 'regex:/^(?=[A-Za-z0-9\-]{8}$)(?=.*\-).*/'],
            'sending_address' => ['required'],
            'sending_building' => ['nullable'],
        ];
    }

    public function messages()
    {
        return [
            'sending_postcode.required' => '郵便番号を入力してください',
            'sending_postcode.regex' => '郵便番号はハイフンを含む8文字で入力してください',
            'sending_address.required' => '住所を入力してください',
        ];
    }
}
