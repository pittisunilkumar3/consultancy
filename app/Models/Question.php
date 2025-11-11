<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'question',
        'help_text',
        'type',
        'options',
        'order',
        'required',
    ];

    protected $casts = [
        'options' => 'array',
        'required' => 'boolean',
    ];

    /**
     * Get all criteria fields mapped to this question
     */
    public function criteriaFields()
    {
        return $this->belongsToMany(UniversityCriteriaField::class, 'question_criteria_mappings', 'question_id', 'criteria_field_id');
    }

    /**
     * Get all criteria mappings for this question
     */
    public function criteriaMappings()
    {
        return $this->hasMany(QuestionCriteriaMapping::class, 'question_id');
    }
}
