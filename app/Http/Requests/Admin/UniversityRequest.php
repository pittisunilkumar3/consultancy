<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UniversityRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'details' => 'required|string',
            'avg_cost' => 'required|string',
            'world_ranking' => 'nullable|string',
            'international_student' => 'nullable|string',
            'country_id' => 'required',
            'thumbnail_image' => $this->id ? 'nullable|mimes:jpeg,png,jpg,svg,webp|max:1024' : 'required|mimes:jpeg,png,jpg,svg,webp|max:1024',
            'logo' => $this->id ? 'nullable|mimes:jpeg,png,jpg,svg,webp|max:1024' : 'required|mimes:jpeg,png,jpg,svg,webp|max:1024',
            'core_benefits_title.*' => 'nullable|string',
            'core_benefits_icon.*' => 'nullable|mimes:jpeg,png,jpg,svg,webp|max:1024',
        ];

        for ($i = 0; $i < 4; $i++) {
            $rules["gallery_image.$i"] = 'nullable|mimes:jpeg,png,jpg,svg,webp|max:1024';
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'thumbnail_image.required' => 'This field is required.',
            'logo.required' => 'This field is required.',
        ];

        return $messages;
    }

}
