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
            'world_ranking' => 'required|string',
            'international_student' => 'required|string',
            'country_id' => 'required',
            'thumbnail_image' => $this->id ? 'nullable|mimes:jpeg,png,jpg,svg,webp|max:1024' : 'required|mimes:jpeg,png,jpg,svg,webp|max:1024',
            'logo' => $this->id ? 'nullable|mimes:jpeg,png,jpg,svg,webp|max:1024' : 'required|mimes:jpeg,png,jpg,svg,webp|max:1024',
        ];

        for ($i = 0; $i < 4; $i++) {
            $rules["gallery_image.$i"] = $this->id ? 'nullable|mimes:jpeg,png,jpg,svg,webp|max:1024' : 'required|mimes:jpeg,png,jpg,svg,webp|max:1024';
        }

        $coreBenefitsCount = count($this->core_benefits_title ?? []);

        for ($i = 0; $i < $coreBenefitsCount; $i++) {
            $rules["core_benefits_icon.$i"] = (empty($this->core_benefits_icon_id[$i]) || $this->hasFile("core_benefits_icon.$i"))
                ? 'required|mimes:jpeg,png,jpg,svg,webp|max:1024'
                : 'nullable|mimes:jpeg,png,jpg,svg,webp|max:1024';

            $rules["core_benefits_title.$i"] = 'required|string';
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'core_benefits_title.*.required' => 'This field is required.',
            'core_benefits_icon.*.required' => 'This field is required.',
            'gallery_image.*.required' => 'This field is required.',
            'thumbnail_image.required' => 'This field is required.',
            'logo.required' => 'This field is required.',
        ];

        return $messages;
    }

}
