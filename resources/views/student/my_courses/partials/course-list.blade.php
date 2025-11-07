<div class="row rg-15">
    @forelse($myCourses as $course)
        <div class="col-xl-3 col-md-4 col-sm-6">
            <div class="course-item-one">
                <div class="course-info">
                    <div class="img">
                        <img src="{{getFileUrl($course->thumbnail)}}" alt="{{$course->title}}"/>
                    </div>
                    <div class="content">
                        <h4 class="title">{{$course->title}}</h4>
                        <p class="fs-15 fw-500 lh-20 text-para-text">{{$course->program_title}}</p>
                    </div>
                </div>
                <div class="course-action">
                    <a href="{{route('student.my_courses.view', encodeId($course->id))}}" class="link">{{__('View Details')}}</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-xl-12">
            <div class="p-lg-50 p-md-30 p-sm-25 p-15 bd-one bd-c-stroke bd-ra-5 bg-white">
                <span class="course-info">{{__('No course found')}}</span>
            </div>
        </div>
    @endforelse
</div>
{{ $myCourses->links('layouts.partial.common_pagination_with_count') }}
