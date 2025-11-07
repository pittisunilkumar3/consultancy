<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FreeConsultation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'mobile',
        'fund_type',
        'assign_by',
        'consultation_type',
        'country_ids',
        'study_level_id',
        'narration',
        'status',
    ];

    protected $casts = [
        'country_ids' => 'array',
    ];

    /**
     * Get the user's full name by concatenating first_name and last_name.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function study_level()
    {
        return $this->belongsTo(StudyLevel::class);
    }

    /**
     * Get the country names based on country_ids.
     *
     * @return array
     */
    public function countries()
    {
        return Country::whereIn('id', $this->country_ids)->pluck('name')->toArray();
    }

    /**
     * Get country names as a comma-separated string.
     *
     * @return string
     */
    public function getCountryNamesAttribute()
    {
        return implode(', ', $this->countries());
    }
}
