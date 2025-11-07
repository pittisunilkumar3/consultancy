<?php

namespace App\Http\Controllers\Admin\Course;

use App\Http\Controllers\Controller;
use App\Models\CourseLecture;
use App\Models\CourseResource;
use App\Models\FileManager;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseResourceController extends Controller
{
    use ResponseTrait;

    public function store(Request $request, $course_id, $lesson_id, $lecture_id)
    {
        $fileRules = 'nullable';
        $mimesMessage = null;

        // Define file rules and specific error messages based on resource type
        if ($request->resource_type == RESOURCE_TYPE_LOCAL) {
            // Local file with video formats
            $fileRules = 'required_if:resource_type,' . RESOURCE_TYPE_LOCAL . '|mimes:mp4,avi,mkv,mov';
            $mimesMessage = __('The file must be a valid video file type: mp4, avi, mkv, mov.');
        } elseif ($request->resource_type == RESOURCE_TYPE_PDF) {
            // PDF file
            $fileRules = 'required_if:resource_type,' . RESOURCE_TYPE_PDF . '|mimes:pdf';
            $mimesMessage = __('The file must be a valid PDF file type: pdf.');
        } elseif ($request->resource_type == RESOURCE_TYPE_IMAGE) {
            // Image file
            $fileRules = 'required_if:resource_type,' . RESOURCE_TYPE_IMAGE . '|mimes:jpeg,png,jpg,gif,svg';
            $mimesMessage = __('The file must be a valid image file type: jpeg, png, jpg, gif, svg.');
        } elseif ($request->resource_type == RESOURCE_TYPE_AUDIO) {
            // Audio file
            $fileRules = 'required_if:resource_type,' . RESOURCE_TYPE_AUDIO . '|mimes:mp3,wav,ogg,m4a';
            $mimesMessage = __('The file must be a valid audio file type: mp3, wav, ogg, m4a.');
        }

        // Perform validation
        $request->validate([
            'resource_name' => 'required|min:2|max:195',
            'resource_type' => 'required|in:1,2,3,4,5,6',
            'duration' => 'required_if:resource_type,' . RESOURCE_TYPE_LOCAL . ',' . RESOURCE_TYPE_YOUTUBE_ID . ',' . RESOURCE_TYPE_AUDIO,
            'file' => $fileRules,
            'youtube_id' => 'required_if:resource_type,' . RESOURCE_TYPE_YOUTUBE_ID,
            'google_slide_link' => 'required_if:resource_type,' . RESOURCE_TYPE_SLIDE,
        ], [
            // Resource Name Validation Messages
            'resource_name.required' => __('The resource name is required.'),
            'resource_name.min' => __('The resource name must be at least 2 characters long.'),
            'resource_name.max' => __('The resource name must not exceed 195 characters.'),

            // Resource Type Validation Messages
            'resource_type.required' => __('The resource type is required.'),
            'resource_type.in' => __('The selected resource type is invalid.'),

            // Duration Validation Message
            'duration.required_if' => __('The duration is required for video, YouTube, or audio files.'),

            // File Validation Messages based on resource type
            'file.required_if' => __('A file is required for the selected resource type.'),
            'file.mimes' => $mimesMessage,  // Using the dynamically set mimes message based on resource type

            // YouTube ID Validation Message
            'youtube_id.required_if' => __('A YouTube ID is required for YouTube videos.'),

            // Google Slide Link Validation Message
            'google_slide_link.required_if' => __('A Google Slide link is required for Google Slide resources.'),
        ]);


        DB::beginTransaction();

        try {
            $resource = new CourseResource();
            $resource->course_id = $course_id;
            $resource->course_lesson_id = $lesson_id;
            $resource->course_lecture_id = $lecture_id;
            $resource->title = $request->resource_name;
            $resource->resource_type = $request->resource_type;
            $resource->duration = $request->duration ?? 0;

            // Handle file upload if it's a file-based resource
            if ($request->hasFile('file') && in_array($request->resource_type, [1, 3, 4, 5, 6])) {
                $fileManager = new FileManager();
                $uploadedFile = $fileManager->upload('courses-resources', $request->file);
                if (!is_null($uploadedFile)) {
                    $resource->resource = $uploadedFile->id;  // Store file ID or path in resource
                } else {
                    DB::rollBack();
                    return $this->error([], __('Something went wrong while uploading the file.'));
                }
            }

            // Handle YouTube ID
            if ($request->resource_type == RESOURCE_TYPE_YOUTUBE_ID) {
                $resource->resource = $request->youtube_id;
            }

            // Handle Google Slide Link
            if ($request->resource_type == RESOURCE_TYPE_SLIDE) {
                $resource->resource = $request->google_slide_link;
            }

            $resource->save();
            DB::commit();
            return $this->success([], __('Resource created successfully!'));
        } catch (Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return $this->error([], __('Something went wrong! Please try again.'));
        }
    }

    public function view($course_id, $lesson_id, $lecture_id , $id)
    {
        $resource = CourseResource::where(['course_id' => $course_id, 'course_lesson_id' => $lesson_id, 'course_lecture_id' => $lecture_id, 'id' => $id])->first();

        return view('admin.courses.lessons.resource-view', [
            'resource' => $resource
        ]);
    }

    public function delete($course_id, $lesson_id, $lecture_id , $id)
    {
        try {
            $courseLesson = CourseResource::where(['course_id' => $course_id, 'course_lesson_id' => $lesson_id, 'course_lecture_id' => $lecture_id, 'id' => $id])->first();
            $courseLesson->delete();

            return $this->success([], __(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], __('Something went wrong! Please try again'));
        }
    }
}
