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
        
        // Get all criteria fields for dependency dropdown (exclude current field when editing)
        $data['allCriteriaFields'] = UniversityCriteriaField::where('status', STATUS_ACTIVE)
            ->orderBy('name')
            ->get(['id', 'name']);

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
                'options' => 'nullable|string',
                'depends_on_criteria_field_id' => 'nullable|exists:university_criteria_fields,id',
                'depends_on_value' => 'nullable|string|max:255',
                'is_structured' => 'nullable|boolean',
            ];

            $request->validate($rules);

            // Parse options if provided (for JSON type)
            $options = null;
            if ($request->filled('options') && $request->type === 'json') {
                $decoded = json_decode($request->options, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $options = $decoded;
                } else {
                    // Try to parse as comma-separated values
                    $optionsArray = array_map('trim', explode(',', $request->options));
                    $options = array_filter($optionsArray);
                }
            }

            $criteriaField = UniversityCriteriaField::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'type' => $request->type,
                'description' => $request->description,
                'status' => $request->status,
                'order' => $request->order ?? 0,
                'options' => $options,
                'depends_on_criteria_field_id' => $request->depends_on_criteria_field_id ?: null,
                'depends_on_value' => $request->depends_on_value ?: null,
                'is_structured' => $request->has('is_structured') && $request->is_structured == '1',
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
                'options' => 'nullable|string',
                'depends_on_criteria_field_id' => 'nullable|exists:university_criteria_fields,id',
                'depends_on_value' => 'nullable|string|max:255',
                'is_structured' => 'nullable|boolean',
            ];

            $request->validate($rules);

            // Prevent circular dependencies
            if ($request->depends_on_criteria_field_id == $id) {
                return response()->json([
                    'status' => false,
                    'message' => __('A field cannot depend on itself')
                ], 422);
            }

            // Parse options if provided (for JSON type)
            $options = null;
            if ($request->filled('options') && $request->type === 'json') {
                $decoded = json_decode($request->options, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $options = $decoded;
                } else {
                    // Try to parse as comma-separated values
                    $optionsArray = array_map('trim', explode(',', $request->options));
                    $options = array_filter($optionsArray);
                }
            } elseif ($request->type !== 'json') {
                // Clear options if type is not JSON
                $options = null;
            }

            $criteriaField->update([
                'name' => $request->name,
                'slug' => $request->slug,
                'type' => $request->type,
                'description' => $request->description,
                'status' => $request->status,
                'order' => $request->order ?? 0,
                'options' => $options,
                'depends_on_criteria_field_id' => $request->depends_on_criteria_field_id ?: null,
                'depends_on_value' => $request->depends_on_value ?: null,
                'is_structured' => $request->has('is_structured') && $request->is_structured == '1',
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
