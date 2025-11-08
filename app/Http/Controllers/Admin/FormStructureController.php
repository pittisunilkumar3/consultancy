<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormStructure;
use App\Models\FormStructureItem;
use App\Models\FormStructureSection;
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
            
            // Remove all existing items and sections (we'll replace with new structure)
            $structure->items()->delete();
            $structure->sections()->delete();
            
            // Process sections first
            if ($request->has('sections')) {
                $this->processSections($request->sections, $structure->id);
            }
            
            // Process standalone items (not in sections)
            if ($request->has('items')) {
                $this->processItems($request->items, $structure->id, null, null, null);
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
     * Process and save sections
     */
    private function processSections($sections, $structureId)
    {
        foreach ($sections as $index => $sectionData) {
            $section = FormStructureSection::create([
                'structure_id' => $structureId,
                'name' => $sectionData['name'],
                'description' => $sectionData['description'] ?? null,
                'order' => $index,
                'is_collapsible' => $sectionData['is_collapsible'] ?? true,
                'is_expanded_by_default' => $sectionData['is_expanded_by_default'] ?? true,
            ]);

            // Process items in this section
            if (isset($sectionData['items']) && is_array($sectionData['items'])) {
                $this->processItems($sectionData['items'], $structureId, null, null, $section->id);
            }
        }
    }

    /**
     * Recursively process and save structure items
     */
    private function processItems($items, $structureId, $parentId = null, $parentOptionValue = null, $sectionId = null)
    {
        foreach ($items as $index => $item) {
            // Create the item
            $structureItem = FormStructureItem::create([
                'structure_id' => $structureId,
                'section_id' => $sectionId,
                'question_id' => $item['question_id'],
                'parent_item_id' => $parentId,
                'parent_option_value' => $parentOptionValue,
                'order' => $index
            ]);

            // Process children if any (for radio questions)
            // Children inherit the section_id from parent
            if (isset($item['children'])) {
                foreach ($item['children'] as $optionValue => $child) {
                    if (isset($child['items'])) {
                        $this->processItems(
                            $child['items'],
                            $structureId,
                            $structureItem->id,
                            $optionValue,
                            $sectionId // Children inherit section from parent
                        );
                    }
                }
            }
        }
    }
}
