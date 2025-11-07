<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Faq;
use App\Models\Program;
use App\Models\Testimonial;

class CourseController extends Controller
{
    public function courseProgram($slug)
    {
        $program = Program::where('status', STATUS_ACTIVE)->where('slug', $slug)->firstOrFail();
        $data['pageTitle'] = __($program->title);
        $data['program'] = $program;
        $data['testimonialData'] = Testimonial::where('status',STATUS_ACTIVE)->get();
        $data['faqData'] = Faq::where('status',STATUS_ACTIVE)->get();
        $data['activeCourseProgram'.$data['program']->id] = 'active';
        $data['activeProgramMenu'] = 'active';
        $data['bodyClass'] = 'bg-white';
        $data['courses'] = Course::where('status', STATUS_ACTIVE)->where('program_id', $program->id)->withCount('lessons')->get();
        return view('frontend.courses.programs', $data);
    }

    public function course($slug)
    {
        $course = Course::where('status', STATUS_ACTIVE)
            ->with(['program', 'lessons'])
            ->where('slug', $slug)
            ->firstOrFail();
        $data['pageTitle'] = __($course->title);
        $data['activeCourseProgram'.$course->program_id] = 'active';
        $data['activeProgramMenu'] = 'active';
        $data['bodyClass'] = 'bg-white';
        $data['course'] = $course;

        return view('frontend.courses.single-course', $data);
    }

    public function video($slug){

        $data['resource'] = Course::where('slug',$slug)->first();
        return view('frontend.courses.resource-view', $data);
    }

}
