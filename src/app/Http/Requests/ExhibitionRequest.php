<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        $this->merge([
            'price' => mb_convert_kana($this->price, 'n'),
        ]);
    }

    public function rules()
    {
        return [
            'name' => ['required'],
            'description' => ['required', 'max:255'],
            'img_url' => ['required', 'mimes:jpeg,png'],
            'category_id' => ['required', 'array','min:1'],
            'category_id.*' => ['integer', 'exists:categories,id'],
            'condition_id' => ['required'],
            'price' => ['required', 'numeric', 'gte:1'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'description.required' => '商品の説明を入力してください',
            'description.max' => '商品の説明は255文字以内で入力してください',
            'img_url.required' => '商品画像をアップロードしてください',
            'img_url.mimes' => '「.jpeg」もしくは「.png」形式でアップロードしてください',
            'category_id.required' => 'カテゴリーを選択してください',
            'category_id.array' => 'カテゴリーを選択してください',
            'category_id.min' => 'カテゴリーを選択してください',
            'category_id.*.integer' => 'カテゴリーを選択してください',
            'category_id.*.exists' => 'カテゴリーを選択してください',
            'condition_id.required' => '商品の状態を選択してください',
            'price.required' => '商品価格を入力してください',
            'price.numeric' => '商品価格は数字で入力してください',
            'price.gte' => '商品価格は0円以上で入力してください',
        ];
    }
}
