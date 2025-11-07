<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LanguageRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'language' => [
                'bail',
                'required',
                Rule::unique('languages', 'language')
                    ->ignore($this->id)
                    ->whereNull('deleted_at')
            ],
        ];

        if (is_null($this->id)) {
            $rules['iso_code'] = [
                'bail',
                'required',
                Rule::unique('languages', 'iso_code')
                    ->whereNull('deleted_at')
            ];
            $rules['flag'] = 'bail|required|image|mimes:jpeg,png,jpg,svg,webp';
        } else {
            $rules['flag'] = 'bail|nullable|image|mimes:jpeg,png,jpg,svg,webp';
        }

        $rules['font'] = 'nullable|file|mimetypes:font/ttf';

        return $rules;
    }

}
