<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
            'title' => 'required|max:195',
            'location' => 'required_if:type,' . EVENT_TYPE_PHYSICAL . '|max:195',
            'date_time' => 'required|date',
            'price' => 'required|numeric|min:0',
            'image' => $this->id ? 'nullable|image|mimes:jpeg,png,jpg,svg,webp' : 'required|image|mimes:jpeg,png,jpg,svg,webp',
            'description' => 'required|min:10',
        ];
    }

    /**
     * Custom error messages.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'location.required_if' => __('The event location is required when the event type is physical.'),
            'image.max' => __('The event image size must not exceed 2MB.'),
            'description.min' => __('The event description must be at least 10 characters long.'),
        ];
    }
}
