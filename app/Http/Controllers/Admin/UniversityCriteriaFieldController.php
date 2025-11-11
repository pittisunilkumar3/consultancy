<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UniversityCriteriaField;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class UniversityCriteriaFieldController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = UniversityCriteriaField::query()->orderBy('order')->orderBy('id', 'DESC');
            return datatables()->of($data)
                ->addIndexColumn()
                ->editColumn('type', function ($row) {
                    $types = [
                        'boolean' => __('Boolean'),
                        'number' => __('Number'),
                        'decimal' => __('Decimal'),
                        'text' => __('Text'),
                        'json' => __('JSON'),
                    ];
                    return $types[$row->type] ?? $row->type;
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == STATUS_ACTIVE) {
                        return '<div class="zBadge zBadge-complete">' . __('Active') . '</div>';
                    } else {
                        return '<div class="zBadge zBadge-deactive">' . __('Inactive') . '</div>';
                    }
                })
                ->addColumn('action', function ($row) {
                    $edit = '<a href="#" class="sf-btn-primary-xs edit-btn" data-id="' . $row->id . '" title="' . __('Edit') . '"><i class="fa-solid fa-pen-to-square"></i></a>';
                    $delete = '<a href="#" class="sf-btn-danger-xs delete-btn" data-id="' . $row->id . '" title="' . __('Delete') . '"><i class="fa-solid fa-trash-can"></i></a>';
                    return '<div class="d-flex g-12">' . $edit . $delete . '</div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        $data['pageTitle'] = __('University Criteria Fields');
        $data['activeCriteriaFields'] = 'active';
        $data['showQuestions'] = 'show';
        $data['activeQuestion'] = 'active';

        return view('admin.university-criteria-fields.index', $data);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $rules = [
                'name' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:university_criteria_fields,slug',
                'type' => 'required|string|in:boolean,number,decimal,text,json',
                'description' => 'nullable|string',
                'status' => 'required|integer|in:' . STATUS_ACTIVE . ',' . STATUS_DEACTIVATE,
                'order' => 'nullable|integer|min:0',
            ];

            $request->validate($rules);

            $criteriaField = UniversityCriteriaField::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'type' => $request->type,
                'description' => $request->description,
                'status' => $request->status,
                'order' => $request->order ?? 0,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => __('Criteria field created successfully'),
                'data' => $criteriaField
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => __('Validation error'),
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => __('Error creating criteria field: ') . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $criteriaField = UniversityCriteriaField::findOrFail($id);
        return response()->json([
            'status' => true,
            'data' => $criteriaField
        ]);
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $criteriaField = UniversityCriteriaField::findOrFail($id);

            $rules = [
                'name' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:university_criteria_fields,slug,' . $id,
                'type' => 'required|string|in:boolean,number,decimal,text,json',
                'description' => 'nullable|string',
                'status' => 'required|integer|in:' . STATUS_ACTIVE . ',' . STATUS_DEACTIVATE,
                'order' => 'nullable|integer|min:0',
            ];

            $request->validate($rules);

            $criteriaField->update([
                'name' => $request->name,
                'slug' => $request->slug,
                'type' => $request->type,
                'description' => $request->description,
                'status' => $request->status,
                'order' => $request->order ?? 0,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => __('Criteria field updated successfully'),
                'data' => $criteriaField
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => __('Validation error'),
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => __('Error updating criteria field: ') . $e->getMessage()
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $criteriaField = UniversityCriteriaField::findOrFail($id);
            $criteriaField->delete();

            return response()->json([
                'status' => true,
                'message' => __('Criteria field deleted successfully')
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => __('Error deleting criteria field: ') . $e->getMessage()
            ], 500);
        }
    }
}
