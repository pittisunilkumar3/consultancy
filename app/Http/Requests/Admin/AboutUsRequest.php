<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AboutUsRequest extends FormRequest
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
            'details' => 'required',
            'our_mission_title' => 'required',
            'our_mission_details' => 'required',
            'our_vision_title' => 'required',
            'our_vision_details' => 'required',
            'our_goal_title' => 'required',
            'our_goal_details' => 'required',
            'our_mission_image' =>$this->id ? 'nullable|mimes:jpeg,png,jpg,svg,webp|max:1024' : 'required|mimes:jpeg,png,jpg,svg,webp|max:1024',
            'our_vision_image' =>$this->id ? 'nullable|mimes:jpeg,png,jpg,svg,webp|max:1024' : 'required|mimes:jpeg,png,jpg,svg,webp|max:1024',
            'our_goal_image' =>$this->id ? 'nullable|mimes:jpeg,png,jpg,svg,webp|max:1024' : 'required|mimes:jpeg,png,jpg,svg,webp|max:1024',
        ];
        for ($i = 0; $i < 4; $i++) {
            $rules["banner_image.$i"] = $this->id ? 'nullable|mimes:jpeg,png,jpg,svg,webp|max:1024' : 'required|mimes:jpeg,png,jpg,svg,webp|max:1024';
        }
        if ($this->has('awards_title')) {
            foreach ($this->input('awards_title') as $key => $name) {
                $rules['awards_title.' . $key] = 'required|string|max:255';

                if ($this->has('id') && isset($this->input('old_awards_image')[$key]) && $this->input('old_awards_image')[$key]) {
                    $rules['awards_image.' . $key] = 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048';
                } else {
                    $rules['awards_image.' . $key] = 'required|image|mimes:jpeg,png,jpg,svg,webp|max:2048';
                }
            }
        }
        if ($this->has('our_history_year')) {
            foreach ($this->input('our_history_year') as $key => $name) {
                $rules['our_history_year.' . $key] = 'required|string|max:255';
                $rules['our_history_title.' . $key] = 'required|string|max:255';
                $rules['our_history_description.' . $key] = 'required|string|max:255';

                if ($this->has('id') && isset($this->input('old_our_history_image')[$key]) && $this->input('old_our_history_image')[$key]) {
                    $rules['our_history_image.' . $key] = 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048';
                } else {
                    $rules['our_history_image.' . $key] = 'required|image|mimes:jpeg,png,jpg,svg,webp|max:2048';
                }
            }
        }
        if ($this->has('about_us_point')) {
            foreach ($this->input('about_us_point') as $key => $name) {
                $rules['about_us_point.' . $key] = 'required|string|max:255';

            }
        }
        return $rules;
    }

    public function messages()
    {
        $messages = [
            'awards_title.*.required' => 'This awards title field is required.',
            'awards_image.*.required' => 'This awards image field is required.',
            'our_history_year.*.required' => 'This our history year field is required.',
            'our_history_title.*.required' => 'This our history title field is required.',
            'our_history_description.*.required' => 'This our history description field is required.',
            'about_us_point.*.required' => 'This our about us details point field is required.',
            'our_history_image.*.required' => 'This our history image field is required.',
            'gallery_image.*.required' => 'This field is required.',
            'thumbnail_image.required' => 'This field is required.',
            'logo.required' => 'This field is required.',
            'banner_image.*.required' => 'This image field are required.'
        ];

        return $messages;
    }

}
