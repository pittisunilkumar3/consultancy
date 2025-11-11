<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UniversityCriteriaField extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'description',
        'status',
        'order',
    ];

    protected $casts = [
        'status' => 'integer',
        'order' => 'integer',
    ];

    /**
     * Get all criteria values for this field
     */
    public function criteriaValues()
    {
        return $this->hasMany(UniversityCriteriaValue::class, 'criteria_field_id');
    }

    /**
     * Get all questions mapped to this criteria field
     */
    public function questions()
    {
        return $this->belongsToMany(Question::class, 'question_criteria_mappings', 'criteria_field_id', 'question_id');
    }
}
