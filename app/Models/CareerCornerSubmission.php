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

        // More detailed comparison could be added here if needed
        return false;
    }
}
