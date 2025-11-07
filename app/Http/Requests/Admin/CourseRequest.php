<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
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
        // Base validation rules for course fields
        $rules = [
            'title' => 'required|string|max:255',
            'program_id' => 'required',
            'duration' => 'required|integer|min:1',
            'start_date' => 'nullable|date',
            'price' => 'required|numeric|min:1',
            'thumbnail' => is_null($this->id) ? 'required|image|mimes:jpeg,png,jpg,svg,webp' : 'nullable|image|mimes:jpeg,png,jpg,svg,webp',
            'intro_video_type' => 'nullable|in:' . RESOURCE_TYPE_LOCAL . ',' . RESOURCE_TYPE_YOUTUBE_ID,
            'intro_file' => 'required_if:intro_video_type,'.RESOURCE_TYPE_LOCAL.'|mimes:mp4,mkv,avi',
            'youtube_id' => 'nullable|required_if:intro_video_type,' . RESOURCE_TYPE_YOUTUBE_ID . '|string|max:255',
            'learn_point_title.*' => 'required|string|max:255',
            'learn_point_text.*' => 'required|string|max:255',
            'benefit_name.*' => 'required|string|max:255',
            'benefit_value.*' => 'required|string|max:255',
            'faq_question.*' => 'required|string|max:500',
            'faq_answer.*' => 'required|string|max:500',
        ];


        // Conditional validation for instructors
        if ($this->has('instructor_name')) {
            foreach ($this->input('instructor_name') as $key => $name) {
                // If the instructor name is provided, validate the corresponding fields
                $rules['instructor_name.' . $key] = 'required|string|max:255';
                $rules['instructor_professional_title.' . $key] = 'required|string|max:255';

                // Check if there's an old photo or a new upload for this instructor
                if ($this->has('id') && isset($this->input('old_instructor_photo')[$key]) && $this->input('old_instructor_photo')[$key]) {
                    // If old instructor photo exists, the new photo is optional
                    $rules['instructor_photo.' . $key] = 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048';
                } else {
                    // If no old instructor photo, the new photo is required
                    $rules['instructor_photo.' . $key] = 'required|image|mimes:jpeg,png,jpg,svg,webp|max:2048';
                }
            }
        }

        return $rules;
    }

    public function messages()
    {
        return [
            // Instructor fields
            'instructor_name.*.required' => __('The name of each instructor is required.'),
            'instructor_professional_title.*.required' => __('The professional title of each instructor is required.'),
            'instructor_photo.*.required' => __('An image is required for each instructor.'),
            'instructor_photo.*.image' => __('The instructor photo must be an image file.'),
            'instructor_photo.*.mimes' => __('The instructor photo must be in one of the following formats: jpeg, png, jpg, svg, or webp.'),
            'instructor_photo.*.max' => __('The instructor photo may not be larger than 2MB.'),

            // Benefit fields
            'benefit_name.*.required' => __('The name of each benefit is required.'),
            'benefit_value.*.required' => __('The value of each benefit is required.'),

            // Learning point fields
            'learn_point_title.*.required' => __('The title of each learning point is required.'),
            'learn_point_text.*.required' => __('The text for each learning point is required.'),

            // FAQ fields
            'faq_question.*.required' => __('Each FAQ question is required.'),
            'faq_answer.*.required' => __('Each FAQ answer is required.'),

            // General fields
            'title.required' => __('The course title is required.'),
            'program_id.required' => __('The course program is required.'),
            'duration.required' => __('The course duration is required.'),
            'thumbnail.required' => __('The course thumbnail image is required.'),

            // Intro file validation
            'intro_file.required_if' => __('A video file is required when the video type is set to Local.'),
            'intro_file.mimes' => __('The intro video must be in one of the following formats: mp4, mkv, avi.'),
        ];
    }
}
