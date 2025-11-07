<?php

namespace App\Http\Controllers\Admin\Course;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseLesson;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseLessonController extends Controller
{
    use ResponseTrait;
    public function index($course_id)
    {
        $data['course'] = Course::where('id', decodeId($course_id))->with(['lessons.lectures', 'enrolls.user'])->first();
        $data['pageTitle'] = __('Course Details');
        $data['showManageCourse'] = 'show';
        $data['activeCourse'] = 'active';
        return view('admin.courses.lessons.index', $data);
    }

    public function edit($course_id, $id)
    {
        $courseLesson = CourseLesson::where(['course_id' => $course_id, 'id' => $id])->first();

        return view('admin.courses.lessons.edit', [
            'courseLesson' => $courseLesson
        ]);
    }

    public function store(Request $request, $course_id)
    {
        $request->validate([
            'title' => 'required|max:195',
            'description' => 'required|min:10',
        ]);

        DB::beginTransaction();
        try {
            $id = $request->get('id', '');
            if ($id) {
                $courseLesson = CourseLesson::where(['course_id' => $course_id, 'id' => $id])->first();
            } else {
                $courseLesson = new CourseLesson();
            }
            $courseLesson->course_id = $course_id;
            $courseLesson->title = $request->title;
            $courseLesson->description = $request->description;
            $courseLesson->save();

            DB::commit();
            $message = $request->id ? __(UPDATED_SUCCESSFULLY) : __(CREATED_SUCCESSFULLY);
            return $this->success([], getMessage($message));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], __('Something went wrong! Please try again'));
        }
    }

    public function delete($course_id, $id)
    {
        try {
            $courseLesson = CourseLesson::where(['course_id' => $course_id, 'id' => $id])->first();
            $courseLesson->delete();

            return $this->success([], __(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], __('Something went wrong! Please try again'));
        }
    }
}
