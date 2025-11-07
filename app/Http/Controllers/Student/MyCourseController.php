<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\CourseLectureFinish;
use App\Models\CourseResource;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;


class MyCourseController extends Controller
{
    use ResponseTrait;

    public function list(Request $request)
    {
        $data['pageTitle'] = __('My Course');
        $data['activeMyCourse'] = 'active';
        $query = CourseEnrollment::join('courses', 'course_enrollments.course_id', '=', 'courses.id')
            ->join('programs', 'courses.program_id', '=', 'programs.id')
            ->where('course_enrollments.status', STATUS_ACTIVE)
            ->where('course_enrollments.user_id', auth()->id())
            ->select([
                'programs.title as program_title',
                'courses.id as course_id',
                'courses.title',
                'courses.program_id',
                'courses.thumbnail',
                'course_enrollments.id'
            ]);

        if ($request->has('search_key')) {
            $query->where('courses.title', 'like', '%' . $request->search_key . '%');
        }

        $data['myCourses'] = $query->orderBy('course_enrollments.id', 'DESC')->paginate(12);

        if ($request->ajax()) {
            return view('student.my_courses.partials.course-list', $data)->render();
        }

        return view('student.my_courses.list', $data);
    }

    public function view(Request $request, $enrollmentId)
    {
        $data['pageTitle'] = __('My Course Details');
        $data['activeMyCourse'] = 'active';

        // Retrieve the course and associated lessons, lectures, and resources
        $data['course'] = Course::join('course_enrollments', 'courses.id', '=', 'course_enrollments.course_id')
            ->where('course_enrollments.status', STATUS_ACTIVE)
            ->where('course_enrollments.id', decodeId($enrollmentId))
            ->where('course_enrollments.user_id', auth()->id())
            ->with(['lessons.lectures.resources' => function ($query) {
                $query->select('title', 'resource_type', 'duration', 'course_lecture_id', 'id');
            }])
            ->select('courses.*', 'course_enrollments.id as enrollment_id')
            ->withTrashed()
            ->first();

        // Get the current resource ID from the request, if available, or set to null if not
        $currentResourceId = $request->input('resource_id') ? decodeId($request->input('resource_id')) : null;

        // Fetch the current resource or default to the first resource for the course
        $data['currentResource'] = $currentResourceId
            ? CourseResource::find($currentResourceId)
            : CourseResource::where('course_id', $data['course']->id)->first();

        // Fetch completed lecture resources for this enrollment and user
        $userId = auth()->id();
        $data['finishedResourceIds'] = CourseLectureFinish::where('enrollment_id', decodeId($enrollmentId))
            ->where('user_id', $userId)
            ->pluck('course_resource_id')
            ->toArray();

        // Pass the current resource ID to the view, either the found ID or the first resource ID
        $data['currentResourceId'] = $data['currentResource'] ? $data['currentResource']->id : null;

        // Check if the resource is already marked as complete
        $alreadyCompleted = CourseLectureFinish::where('enrollment_id', decodeId($enrollmentId))
            ->where('user_id', $userId)
            ->where('course_resource_id', $data['currentResourceId'])
            ->exists();

        if (!$alreadyCompleted && $data['currentResourceId']) {
            // Mark the resource as complete
            CourseLectureFinish::create([
                'enrollment_id' => decodeId($enrollmentId),
                'user_id' => $userId,
                'course_resource_id' => $data['currentResourceId'],
            ]);
        }

        return view('student.my_courses.details', $data);
    }
}
