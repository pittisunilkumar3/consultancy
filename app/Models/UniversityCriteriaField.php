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
        'options',
        'depends_on_criteria_field_id',
        'depends_on_value',
        'is_structured',
    ];

    protected $casts = [
        'status' => 'integer',
        'order' => 'integer',
        'options' => 'array',
        'is_structured' => 'boolean',
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

    /**
     * Get the parent criteria field this field depends on
     */
    public function dependsOn()
    {
        return $this->belongsTo(UniversityCriteriaField::class, 'depends_on_criteria_field_id');
    }

    /**
     * Get all criteria fields that depend on this field
     */
    public function dependentFields()
    {
        return $this->hasMany(UniversityCriteriaField::class, 'depends_on_criteria_field_id');
    }

    /**
     * Check if this field should be shown/required based on parent field value
     */
    public function shouldBeShown($parentValue)
    {
        if (!$this->depends_on_criteria_field_id) {
            return true; // No dependency, always show
        }

        if ($this->depends_on_value === null) {
            return true; // No specific value required
        }

        // Compare parent value with required value
        return (string)$parentValue === (string)$this->depends_on_value;
    }
}
