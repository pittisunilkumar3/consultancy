<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormStructure;
use App\Models\FormStructureItem;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormStructureController extends Controller
{
    /**
     * Show the form structure builder page
     */
    public function index()
    {
        // Ensure Career Corner structure exists
        $structure = FormStructure::createCareerCorner();
        $questions = Question::orderBy('order')->get();
        
        return view('admin.form-structure.index', [
            'structure' => $structure,
            'questions' => $questions,
            'pageTitle' => __('Questions Structure')
        ]);
    }

    /**
     * Get the current structure and available questions
     */
    public function getStructure($id)
    {
        $structure = FormStructure::findOrFail($id);
        
        return response()->json([
            'status' => true,
            'data' => [
                'structure' => $structure,
                'items' => $structure->loadNestedStructure(),
                'available_questions' => Question::orderBy('order')->get()
            ]
        ]);
    }

    /**
     * Save the form structure
     */
    public function saveStructure(Request $request, $id)
    {
        $structure = FormStructure::findOrFail($id);
        
        try {
            DB::beginTransaction();
            
            // Remove all existing items (we'll replace with new structure)
            $structure->items()->delete();
            
            // Process the items recursively
            if ($request->has('items')) {
                $this->processItems($request->items, $structure->id);
            }
            
            DB::commit();
            
            return response()->json([
                'status' => true,
                'message' => 'Form structure saved successfully',
                'data' => [
                    'structure' => $structure,
                    'items' => $structure->loadNestedStructure()
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error saving form structure: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Recursively process and save structure items
     */
    private function processItems($items, $structureId, $parentId = null, $parentOptionValue = null)
    {
        foreach ($items as $index => $item) {
            // Create the item
            $structureItem = FormStructureItem::create([
                'structure_id' => $structureId,
                'question_id' => $item['question_id'],
                'parent_item_id' => $parentId,
                'parent_option_value' => $parentOptionValue,
                'order' => $index
            ]);

            // Process children if any (for radio questions)
            if (isset($item['children'])) {
                foreach ($item['children'] as $optionValue => $child) {
                    if (isset($child['items'])) {
                        $this->processItems(
                            $child['items'],
                            $structureId,
                            $structureItem->id,
                            $optionValue
                        );
                    }
                }
            }
        }
    }
}