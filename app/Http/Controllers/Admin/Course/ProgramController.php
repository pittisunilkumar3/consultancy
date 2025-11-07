<?php

namespace App\Http\Controllers\Admin\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProgramRequest;
use App\Models\FileManager;
use App\Models\Program;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Str;

class ProgramController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $programs = Program::orderByDesc('id');

            return datatables($programs)
                ->addIndexColumn()
                ->editColumn('status', function ($data) {
                    return $data->status == STATUS_ACTIVE
                        ? "<p class='zBadge zBadge-active'>" . __('Active') . "</p>"
                        : "<p class='zBadge zBadge-inactive'>" . __('Deactivate') . "</p>";
                })
                ->addColumn('action', function ($data) {
                    return '<div class="d-flex align-items-center g-10 justify-content-end">
                        <button onclick="getEditModal(\'' . route('admin.courses.programs.edit', encodeId($data->id)) . '\', \'#edit-modal\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25"  title="Edit">
                             ' . view('partials.icons.edit')->render() . '
                        </button>
                        <button onclick="deleteItem(\'' . route('admin.courses.programs.delete', encodeId($data->id)) . '\', \'programDataTable\')" class="align-items-center bd-c-stroke bd-one bg-transparent d-flex h-25 justify-content-center rounded-circle w-25" title="Delete">
                            ' . view('partials.icons.delete')->render() . '
                        </button>
                    </div>';
                })
                ->rawColumns(['title', 'status', 'action'])
                ->make(true);
        }

        $data['pageTitle'] = __('Program');
        $data['showManageCourse'] = 'show';
        $data['activeProgram'] = 'active';

        return view('admin.courses.programs.index', $data);
    }


    public function edit($id)
    {
        $data['program'] = Program::where('id', decodeId($id))->first();
        return view('admin.courses.programs.edit', $data);
    }

    public function store(ProgramRequest $request)
    {
        DB::beginTransaction();
        try {
            $id = $request->get('id', '');

            // Check if the program exists or create a new one
            $program = $id ? Program::findOrFail($id) : new Program();

            if ($id) {
                $slug = $program->slug;
            } else {
                if (Program::where('slug', getSlug($request->title))->where('id', '!=', $id)->withTrashed()->count() > 0) {
                    $slug = getSlug($request->title) . '-' . rand(100000, 999999);
                } else {
                    $slug = getSlug($request->title);
                }
            }

            // Fill the program model with the request data
            $program->slug = $slug;
            $program->title = $request->title;
            $program->status = $request->status;

            // Handling top section data
            $topSection = $request->top_section; // Retrieve the top_section array from the request

            if ($request->hasFile('top_section.image')) {
                $new_file = new FileManager();
                $uploaded = $new_file->upload('programs', $request->file('top_section.image'));
                if (!is_null($uploaded)) {
                    $topSection['image'] = $uploaded->id; // Save the uploaded image ID in the array
                } else {
                    DB::rollBack();
                    return $this->error([], getMessage(SOMETHING_WENT_WRONG));
                }
            } else {
                $topSection['image'] = $program->top_section['image'] ?? null;
            }

            // Handling step section data
            $stepSection = $request->step_section; // Retrieve the step_section array from the request

            if ($request->hasFile('step_section.image')) {
                $new_file = new FileManager();
                $uploaded = $new_file->upload('programs', $request->file('step_section.image'));
                if (!is_null($uploaded)) {
                    $stepSection['image'] = $uploaded->id; // Save the uploaded image ID in the array
                } else {
                    DB::rollBack();
                    return $this->error([], getMessage(SOMETHING_WENT_WRONG));
                }
            } else {
                $stepSection['image'] = $program->step_section['image'] ?? null;
            }

            // Save top_section and step_section as arrays in the model
            $program->top_section = $topSection;
            $program->step_section = $stepSection;

            // Saving created_by as the authenticated user's ID
            $program->created_by = auth()->id();

            // Save the program
            $program->save();

            DB::commit();

            $message = $request->id ? __(UPDATED_SUCCESSFULLY) : __(CREATED_SUCCESSFULLY);
            return $this->success([], getMessage($message));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], __('Something went wrong! Please try again'));
        }
    }


    public function delete($id)
    {
        try {
            $program = Program::findOrFail(decodeId($id));
            $program->delete();

            return $this->success([], __(DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], __('Something went wrong! Please try again'));
        }
    }

}
