<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProgramRequest extends FormRequest
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
        return [
            'title' => ['required', 'max:120'],
            'status' => ['required'],

            // Top section fields
            'top_section.title' => ['required', 'max:120'],
            'top_section.image' => [
                $this->id ? 'nullable' : 'required',
                'image',
                'mimes:jpeg,png,jpg,svg,webp',
            ],
            'top_section.details' => ['required'],

            // Step section fields
            'step_section.title' => ['required', 'max:120'],
            'step_section.image' => [
                $this->id ? 'nullable' : 'required',
                'image',
                'mimes:jpeg,png,jpg,svg,webp',
            ],
            'step_section.details' => ['required'],
        ];
    }

    /**
     * Custom messages for validation errors.
     *
     * @return array<string, mixed>
     */
    public function messages()
    {
        return [
            'title.required' => __('The title is required.'),
            'title.max' => __('The title may not be greater than :max characters.'),

            'status.required' => __('The status is required.'),

            // Top section messages
            'top_section.title.required' => __('The top section title is required.'),
            'top_section.title.max' => __('The top section title may not be greater than :max characters.'),
            'top_section.image.required' => __('The top section image is required.'),
            'top_section.image.image' => __('The top section file must be an image.'),
            'top_section.image.mimes' => __('The top section image must be a file of type: :values.'),
            'top_section.details.required' => __('The top section details are required.'),

            // Step section messages
            'step_section.title.required' => __('The step section title is required.'),
            'step_section.title.max' => __('The step section title may not be greater than :max characters.'),
            'step_section.image.required' => __('The step section image is required.'),
            'step_section.image.image' => __('The step section file must be an image.'),
            'step_section.image.mimes' => __('The step section image must be a file of type: :values.'),
            'step_section.details.required' => __('The step section details are required.'),
        ];
    }
}
