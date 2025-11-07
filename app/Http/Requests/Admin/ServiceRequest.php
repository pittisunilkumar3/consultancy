<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
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
            'title' => 'required',
            'description' => 'required',
            'price' => 'required',
            'icon' => $this->id ? 'nullable|mimes:jpeg,png,jpg,svg,webp|max:1024' : 'required|mimes:jpeg,png,jpg,svg,webp|max:1024',
            'image' => $this->id ? 'nullable|mimes:jpeg,png,jpg,svg,webp|max:1024' : 'required|mimes:jpeg,png,jpg,svg,webp|max:1024',
        ];

        if ($this->has('feature_name')) {
            foreach ($this->input('feature_name') as $key => $name) {
                $rules['feature_name.' . $key] = 'required|string|max:255';
                $rules['feature_value.' . $key] = 'required|string|max:255';

            }
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'title.*.required' => __('The title of each services is required.'),
            'description.*.required' => __('The description of each services is required.'),
            'price.*.required' => __('The price of each services is required.'),

            'feature_name.*.required' => __('Each feature name is required.'),
            'feature_value.*.required' => __('Each feature value is required.'),
        ];
    }
}
