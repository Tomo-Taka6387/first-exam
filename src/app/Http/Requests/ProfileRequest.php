<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\Profile;

class ProfileRequest extends FormRequest
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
            'img_url' => ['nullable', 'file', 'mimes:jpeg,png'],
            'name' => ['required', 'max:20'],
            'postcode' => ['required', 'regex:/^(?=[A-Za-z0-9\-]{8}$)(?=.*\-).*/'],
            'address' => ['required'],
            'building' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages()
    {
        return [
            'img_url.mimes' => '「.jpeg」もしくは「.png」形式でアップロードしてください',
            'name.required' => 'ユーザー名を入力してください',
            'name.max' => 'ユーザー名は20文字以下で入力してください',
            'postcode.required' => '郵便番号を入力してください',
            'postcode.regex' => '郵便番号はハイフンを含む8文字で入力してください',
            'address.required' => '住所を入力してください',
        ];
    }
}
