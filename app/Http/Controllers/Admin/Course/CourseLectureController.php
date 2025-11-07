<?php

namespace App\Http\Controllers\Admin\Course;

use App\Http\Controllers\Controller;
use App\Models\CourseLecture;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseLectureController extends Controller
{
    use ResponseTrait;

    public function store(Request $request, $course_id, $lesson_id)
    {
        $request->validate([
            'lecture_title' => 'required|min:2|max:195',
        ]);

        DB::beginTransaction();

        try {
            $id = $request->get('id', '');
            if ($id) {
                $courseLesson = CourseLecture::where(['course_id' => $course_id, 'id' => $id])->first();
            } else {
                $courseLesson = new CourseLecture;
            }
            $courseLesson->course_id = $course_id;
            $courseLesson->course_lesson_id = $lesson_id;
            $courseLesson->title = $request->lecture_title;
            $courseLesson->save();

            DB::commit();
            $message = $request->id ? __(UPDATED_SUCCESSFULLY) : __(CREATED_SUCCESSFULLY);
            return $this->success([], getMessage($message));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], __('Something went wrong! Please try again'));
        }
    }

    public function delete($course_id, $lesson_id, $id)
    {
        try {
            $courseLesson = CourseLecture::where(['course_id' => $course_id, 'course_lesson_id' => $lesson_id, 'id' => $id])->first();
            $courseLesson->delete();

            return $this->success([], __(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], __('Something went wrong! Please try again'));
        }
    }
}
