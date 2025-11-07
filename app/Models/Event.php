<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'type',
        'slug',
        'date_time',
        'location',
        'link',
        'description',
        'price',
        'country_ids',
        'university_ids',
        'study_levels',
        'image',
        'status',
    ];

    /**
     * Cast these attributes as arrays.
     */
    protected $casts = [
        'country_ids' => 'array',
        'university_ids' => 'array',
        'study_levels' => 'array',
    ];

    /**
     * Custom method to retrieve universities based on university_ids array.
     */
    public function getUniversities()
    {
        return University::whereIn('id', $this->university_ids)->get();
    }

    /**
     * Custom method to retrieve countries based on country_ids array.
     */
    public function getCountries()
    {
        return Country::whereIn('id', $this->country_ids)->get();
    }

    /**
     * Custom method to retrieve study levels based on study_levels array.
     */
    public function getStudyLevels()
    {
        return StudyLevel::whereIn('id', $this->study_levels)->get();
    }

    public function payments(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }

    public function percipients()
    {
        return $this->hasManyThrough(User::class, Payment::class, 'paymentable_id', 'id', 'id', 'user_id')
            ->where('paymentable_type', self::class)
            ->where('payment_status', PAYMENT_STATUS_PAID);
    }

}
