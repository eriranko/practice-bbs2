<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BbsRequest extends FormRequest
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
            'title' => 'required|max:25',
            'content' => 'required',
            'categories' => 'array', // カテゴリーは配列として受け取る
            'categories.*' => 'exists:categories,id', // 各カテゴリーIDが存在することを確認
        ];
    }
}

