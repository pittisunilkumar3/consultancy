<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UniversityCriteriaValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'university_id',
        'criteria_field_id',
        'value',
    ];

    /**
     * Get the university that has this criteria value
     */
    public function university()
    {
        return $this->belongsTo(University::class, 'university_id');
    }

    /**
     * Get the criteria field this value belongs to
     */
    public function criteriaField()
    {
        return $this->belongsTo(UniversityCriteriaField::class, 'criteria_field_id');
    }
}
