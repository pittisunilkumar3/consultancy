<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CareerCornerSubmission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'form_structure_id',
        'form_data',
        'form_structure_snapshot',
        'status',
        'notes',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'form_data' => 'array',
        'form_structure_snapshot' => 'array',
        'status' => 'integer',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Get the user (student) who submitted the form
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the form structure this submission belongs to
     */
    public function formStructure()
    {
        return $this->belongsTo(FormStructure::class, 'form_structure_id');
    }

    /**
     * Get the admin who reviewed this submission
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get the form structure data for this submission
     * Uses snapshot if available, otherwise falls back to current structure
     */
    public function getFormStructureData()
    {
        // If snapshot exists, use it
        if ($this->form_structure_snapshot) {
            return $this->form_structure_snapshot;
        }

        // Otherwise, use current structure
        if ($this->formStructure) {
            return [
                'structure' => $this->formStructure->loadNestedStructure(),
                'questions' => $this->formStructure->items()
                    ->with('question')
                    ->get()
                    ->pluck('question')
                    ->filter()
                    ->keyBy('id')
                    ->map(function ($question) {
                        return [
                            'id' => $question->id,
                            'key' => $question->key,
                            'question' => $question->question,
                            'type' => $question->type,
                            'options' => $question->options,
                            'required' => $question->required,
                        ];
                    })
                    ->toArray(),
            ];
        }

        return null;
    }

    /**
     * Check if the form structure has changed since submission
     */
    public function hasStructureChanged()
    {
        // If no snapshot, we can't determine if it changed
        if (!$this->form_structure_snapshot) {
            return false;
        }

        // If form structure doesn't exist anymore, it definitely changed
        if (!$this->formStructure) {
            return true;
        }

        // Get current structure
        $currentStructure = $this->formStructure->loadNestedStructure();
        $snapshotStructure = $this->form_structure_snapshot['structure'] ?? null;

        // Simple comparison: if structure count differs, it changed
        if (!$snapshotStructure || count($currentStructure) !== count($snapshotStructure)) {
            return true;
        }

        // More detailed comparison: check if question IDs differ
        $currentQuestionIds = $this->extractQuestionIdsFromStructure($currentStructure);
        $snapshotQuestionIds = $this->extractQuestionIdsFromStructure($snapshotStructure);

        // Sort arrays for comparison
        sort($currentQuestionIds);
        sort($snapshotQuestionIds);

        // If question IDs differ, structure has changed
        if ($currentQuestionIds !== $snapshotQuestionIds) {
            return true;
        }

        return false;
    }

    /**
     * Extract all question IDs from a structure array
     */
    protected function extractQuestionIdsFromStructure(array $structure): array
    {
        $questionIds = [];

        foreach ($structure as $element) {
            if ($element['type'] === 'section' && isset($element['items'])) {
                // Convert to array if it's a Collection
                $items = is_array($element['items']) ? $element['items'] : (is_object($element['items']) ? $element['items']->toArray() : []);
                $questionIds = array_merge($questionIds, $this->extractQuestionIdsFromItems($items));
            } elseif ($element['type'] === 'item' && isset($element['item'])) {
                // Convert to array if it's a Collection
                $item = is_array($element['item']) ? $element['item'] : (is_object($element['item']) ? $element['item']->toArray() : []);
                $questionIds = array_merge($questionIds, $this->extractQuestionIdsFromItems([$item]));
            }
        }

        return array_unique($questionIds);
    }

    /**
     * Extract question IDs from items array
     */
    protected function extractQuestionIdsFromItems(array $items): array
    {
        $questionIds = [];

        foreach ($items as $item) {
            // Convert to array if it's a Collection or object
            $itemArray = is_array($item) ? $item : (is_object($item) ? (method_exists($item, 'toArray') ? $item->toArray() : (array)$item) : []);
            
            if (isset($itemArray['question_id']) && is_numeric($itemArray['question_id'])) {
                $questionIds[] = (int)$itemArray['question_id'];
            }

            // Recursively check nested items
            if (isset($itemArray['items'])) {
                // Convert to array if it's a Collection
                $nestedItems = is_array($itemArray['items']) ? $itemArray['items'] : (is_object($itemArray['items']) ? (method_exists($itemArray['items'], 'toArray') ? $itemArray['items']->toArray() : []) : []);
                if (!empty($nestedItems)) {
                    $questionIds = array_merge($questionIds, $this->extractQuestionIdsFromItems($nestedItems));
                }
            }
        }

        return $questionIds;
    }
}
