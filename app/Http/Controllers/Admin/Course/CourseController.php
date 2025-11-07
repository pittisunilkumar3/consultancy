<?php

namespace App\Http\Controllers\Admin\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CourseRequest;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\FileManager;
use App\Models\Gateway;
use App\Models\Program;
use App\Models\User;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $courses = Course::select(['title', 'id', 'thumbnail', 'duration', 'start_date', 'status', 'program_id', 'price'])
                ->with('program')
                ->orderByDesc('id');


            // Apply search filter
            if ($request->has('search_key') && $request->search_key != '') {
                $courses->where(function ($query) use ($request) {
                    $query->where('title', 'like', '%' . $request->search_key . '%')
                        ->orWhere('price', 'like', '%' . $request->search_key . '%')
                        ->orWhere('duration', 'like', '%' . $request->search_key . '%')
                        ->orWhereHas('program', function ($q) use ($request) {
                            $q->where('title', 'like', '%' . $request->search_key . '%'); // Filter by program title
                        });
                });
            }

            // Apply program filter
            if ($request->has('program_id') && $request->program_id != '') {
                $courses->where('program_id', $request->program_id);
            }

            return datatables($courses)
                ->editColumn('status', function ($data) {
                    return $data->status == STATUS_ACTIVE
                        ? "<p class='zBadge zBadge-active'>" . __('Published') . "</p>"
                        : "<p class='zBadge zBadge-inactive'>" . __('Deactivate') . "</p>";
                })
                ->addColumn('program', function ($data) {
                    return $data->program->title;
                })
                ->editColumn('start_date', function ($data) {
                    return $data->start_date ? date_format($data->start_date, "d-m-Y") : __('N/A');
                })
                ->editColumn('price', function ($data) {
                    return showPrice($data->price);
                })
                ->editColumn('thumbnail', function ($data) {
                    return '<div class="min-w-160 d-flex align-items-center cg-10">
                        <div class="flex-shrink-0 w-41 h-41 bd-one bd-c-stroke rounded-circle overflow-hidden bg-eaeaea d-flex justify-content-center align-items-center">
                            <img src="' . getFileUrl($data->thumbnail) . '" alt="Image" class="rounded avatar-xs w-100 h-100 object-fit-cover">
                        </div>
                    </div>';
                })
                ->addColumn('action', function ($data) {
                    return '<div class="d-flex align-items-center g-10 justify-content-end">
                        <a href="' . route('admin.courses.lessons.index', encodeId($data->id)) . '" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25"  title="' . __('Module') . '">
                             ' . view('partials.icons.view')->render() . '
                        </a>
                         <button href="" onclick="getEditModal(\'' . route('admin.courses.enrolls', encodeId($data->id)) . '\', \'#enroll-modal\')"  class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25"  title="' . __('Enroll') . '">
                             ' . view('partials.icons.enroll')->render() . '
                        </button>
                         <a href="' . route('admin.courses.edit', encodeId($data->id)) . '" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25"  title="' . __('Edit') . '">
                             ' . view('partials.icons.edit')->render() . '
                        </a>
                        <button onclick="deleteItem(\'' . route('admin.courses.delete', encodeId($data->id)) . '\', \'courseDataTable\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="' . __('Delete') . '">
                            ' . view('partials.icons.delete')->render() . '
                        </button>
                    </div>';
                })
                ->rawColumns(['status', 'action', 'thumbnail'])
                ->make(true);
        }

        $data['pageTitle'] = __('Courses');
        $data['showManageCourse'] = 'show';
        $data['activeCourse'] = 'active';
        $data['programs'] = Program::where('status', STATUS_ACTIVE)->get();

        return view('admin.courses.index', $data);
    }

    public function create()
    {
        $data['pageTitle'] = __('Create');
        $data['pageTitleParent'] = __('Courses');
        $data['showManageCourse'] = 'show';
        $data['activeCourse'] = 'active';
        $data['programs'] = Program::where('status', STATUS_ACTIVE)->get();

        return view('admin.courses.create', $data);
    }

    public function edit($id)
    {
        $data['course'] = Course::where('id', decodeId($id))->first();
        $data['pageTitle'] = __('Update');
        $data['pageTitleParent'] = __('Courses');
        $data['showManageCourse'] = 'show';
        $data['activeCourse'] = 'active';
        $data['programs'] = Program::where('status', STATUS_ACTIVE)->get();
        return view('admin.courses.edit', $data);
    }

    public function store(CourseRequest $request)
    {
        DB::beginTransaction();
        try {
            // Check if we are updating or creating a new course
            $id = $request->get('id', '');
            $course = $id ? Course::findOrFail($id) : new Course(); // Find existing course or create new

            if (Course::where('slug', getSlug($request->title))->where('id', '!=', $id)->withTrashed()->count() > 0) {
                $slug = getSlug($request->title) . '-' . rand(100000, 999999);
            } else {
                $slug = getSlug($request->title);
            }

            // Set course attributes from the request
            $course->title = $request->input('title');
            $course->subtitle = $request->input('subtitle');
            $course->slug = $slug;
            $course->program_id = $request->input('program_id');
            $course->duration = $request->input('duration');
            $course->start_date = $request->input('start_date');
            $course->price = $request->input('price');
            $course->description = $request->input('description');
            $course->status = $request->input('status', STATUS_DEACTIVATE); // Set status
            $course->meta_title = $request->input('meta_title');
            $course->meta_description = $request->input('meta_description');
            $course->meta_keywords = $request->input('meta_keywords');

            // Handle thumbnail image upload
            if ($request->hasFile('thumbnail')) {
                $fileManager = new FileManager();
                $uploadedThumbnail = $fileManager->upload('courses', $request->thumbnail);
                if (!is_null($uploadedThumbnail)) {
                    $course->thumbnail = $uploadedThumbnail->id;
                } else {
                    DB::rollBack();
                    return $this->error([], __('Something went wrong while uploading the thumbnail.'));
                }
            }

            // Handle intro video upload (if it's a local file)
            if ($request->hasFile('intro_file') && $request->intro_video_type == RESOURCE_TYPE_LOCAL) {
                $fileManager = new FileManager();
                $uploadedVideo = $fileManager->upload('courses/videos', $request->intro_file);
                if (!is_null($uploadedVideo)) {
                    $course->intro_video = $uploadedVideo->id;
                    $course->intro_video_type = RESOURCE_TYPE_LOCAL;
                } else {
                    DB::rollBack();
                    return $this->error([], __('Something went wrong while uploading the intro video.'));
                }
            } elseif ($request->input('intro_video_type') == RESOURCE_TYPE_YOUTUBE_ID) {
                $course->intro_video = $request->input('youtube_id');
                $course->intro_video_type = RESOURCE_TYPE_YOUTUBE_ID;
            }

            // Handle FAQs
            $faqs = [];
            if ($request->has('faq_question')) {
                foreach ($request->input('faq_question') as $key => $question) {
                    $faqs[] = [
                        'question' => $question,
                        'answer' => $request->input('faq_answer.' . $key),
                    ];
                }
            }
            $course->faqs = $faqs; // Store the FAQ array as JSON

            // Handle instructor data, including image upload
            $instructors = [];
            if ($request->has('instructor_name')) {
                foreach ($request->input('instructor_name') as $key => $name) {
                    $instructorData = [
                        'name' => $name,
                        'professional_title' => $request->input("instructor_professional_title.$key"),
                    ];

                    // Check if there is an old photo in case of updating
                    $oldPhoto = $request->input("old_instructor_photo.$key");

                    // Handle instructor image upload
                    if ($request->hasFile("instructor_photo.$key")) {
                        $fileManager = new FileManager();
                        $uploadedImage = $fileManager->upload('instructors', $request->file("instructor_photo.$key"));
                        if (!is_null($uploadedImage)) {
                            // Set the new uploaded photo
                            $instructorData['photo'] = $uploadedImage->id;
                        } else {
                            DB::rollBack();
                            return $this->error([], __('Something went wrong while uploading the instructor photo.'));
                        }
                    } elseif ($oldPhoto) {
                        // If no new photo is uploaded, use the old photo
                        $instructorData['photo'] = $oldPhoto;
                    } else {
                        // If no photo is uploaded and no old photo exists, skip adding the 'photo' key
                        $instructorData['photo'] = null;
                    }

                    $instructors[] = $instructorData;
                }
            }
            $course->instructors = $instructors; // Store the instructors array as JSON


            // Handle Benefits
            $benefits = [];
            if ($request->has('benefit_name')) {
                foreach ($request->input('benefit_name') as $key => $benefit_name) {
                    $benefits[] = [
                        'name' => $benefit_name,
                        'value' => $request->input("benefit_value.$key"),
                    ];
                }
            }
            $course->course_benefits = $benefits; // Store benefits as JSON

            // Handle Learning Points
            $learn_points = [];
            if ($request->has('learn_point_title')) {
                foreach ($request->input('learn_point_title') as $key => $learn_point_title) {
                    $learn_points[] = [
                        'title' => $learn_point_title,
                        'text' => $request->input("learn_point_text.$key"),
                    ];
                }
            }
            $course->description_point = $learn_points; // Store learning points as JSON

            $course->created_by = auth()->id();

            // Save the course to the database
            $course->save();

            // Commit the transaction
            DB::commit();

            // Return success response
            $message = $id ? __('Updated Successfully') : __('Created Successfully');
            return $this->success([], getMessage($message));

        } catch (Exception $e) {
            // Rollback transaction if an error occurs
            DB::rollBack();
            return $this->error([], __('Something went wrong! Please try again.'));
        }
    }

    public function delete($id)
    {
        try {
            $course = Course::findOrFail(decodeId($id));
            $course->delete();

            return $this->success([], __(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], __('Something went wrong! Please try again'));
        }
    }

    public function enrolls($courseId)
    {
        $data['course'] = Course::findOrFail(decodeId($courseId));
        $data['students'] = User::where(['role' => USER_ROLE_STUDENT, 'status' => STATUS_ACTIVE])->get();
        $data['gateways'] = Gateway::where('status', STATUS_ACTIVE)->get();
        return view('admin.courses.enrolls', $data);
    }

    public function enrollRevoke($enrollmentId)
    {
        CourseEnrollment::where('id', $enrollmentId)->update([
            'status' => STATUS_DEACTIVATE
        ]);

        return $this->success([], __('Enroll Successfully'));
    }

    public function enrollUser(Request $request, $courseId)
    {
        $course = Course::findOrFail(decodeId($courseId));

        //set in enrollment
        CourseEnrollment::create([
            'user_id' => $request->student,
            'course_id' => $course->id,
        ]);

        return $this->success([], __('Enroll Successfully'));
    }
}
