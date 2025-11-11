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
            
            // Process elements in order (sections and items mixed)
            if ($request->has('elements') && is_array($request->elements)) {
                foreach ($request->elements as $element) {
                    if (isset($element['type'])) {
                        if ($element['type'] === 'section') {
                            // Process section
                            $section = FormStructureSection::create([
                                'structure_id' => $structure->id,
                                'name' => $element['name'],
                                'description' => $element['description'] ?? null,
                                'order' => $element['order'] ?? 0,
                                'is_collapsible' => $element['is_collapsible'] ?? true,
                                'is_expanded_by_default' => $element['is_expanded_by_default'] ?? true,
                            ]);

                            // Process items in this section
                            if (isset($element['items']) && is_array($element['items'])) {
                                $this->processItems($element['items'], $structure->id, null, null, $section->id);
                            }
                        } elseif ($element['type'] === 'item') {
                            // Process standalone item with order
                            if (isset($element['item'])) {
                                $itemData = $element['item'];
                                $itemData['order'] = $element['order'] ?? 0;
                                $this->processItems([$itemData], $structure->id, null, null, null);
                            }
                        }
                    }
                }
            }
            
            // Backward compatibility: also handle old format (sections and items separately)
            if ($request->has('sections') && !$request->has('elements')) {
                $this->processSections($request->sections, $structure->id);
            }
            
            if ($request->has('items') && !$request->has('elements')) {
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
     * Toggle publish status of the form structure
     */
    public function togglePublish($id)
    {
        $structure = FormStructure::findOrFail($id);
        
        try {
            $structure->is_published = !$structure->is_published;
            $structure->save();
            
            $status = $structure->is_published ? 'published' : 'unpublished';
            $message = $structure->is_published 
                ? 'Form has been published and is now live on Career Corner page' 
                : 'Form has been unpublished and is no longer visible to students';
            
            return response()->json([
                'status' => true,
                'message' => $message,
                'data' => [
                    'structure' => $structure,
                    'is_published' => $structure->is_published
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error updating publish status: ' . $e->getMessage()
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
            // Create the item - use order from item data if provided, otherwise use index
            $structureItem = FormStructureItem::create([
                'structure_id' => $structureId,
                'section_id' => $sectionId,
                'question_id' => $item['question_id'],
                'parent_item_id' => $parentId,
                'parent_option_value' => $parentOptionValue,
                'order' => isset($item['order']) ? $item['order'] : $index
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
