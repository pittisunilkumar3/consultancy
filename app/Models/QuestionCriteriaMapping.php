<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionCriteriaMapping extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'criteria_field_id',
    ];

    /**
     * Get the question this mapping belongs to
     */
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    /**
     * Get the criteria field this mapping belongs to
     */
    public function criteriaField()
    {
        return $this->belongsTo(UniversityCriteriaField::class, 'criteria_field_id');
    }
}
