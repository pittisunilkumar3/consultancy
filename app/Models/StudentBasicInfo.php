<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentBasicInfo extends Model
{
    protected $fillable = [
        'user_id',
        'student_service_order_id',
        'first_name',
        'last_name',
        'email',
        'mobile',
        'gender',
        'destination_country_ids',
        'university_ids',
        'subject_ids',
        'date_of_birth',
        'present_address',
        'permanent_address',
        'admission_period',
        'passport_number',
        'passport_attachment',
        'custom_fields'
    ];

    protected $casts = [
        'destination_country_ids' => 'array',
        'university_ids' => 'array',
        'subject_ids' => 'array',
    ];

    public function getCountries()
    {
        return Country::whereIn('id', $this->destination_country_ids ?? [])->get();
    }

    public function getUniversities()
    {
        return University::whereIn('id', $this->university_ids ?? [])->get();
    }

    public function getSubjects()
    {
        return University::whereIn('id', $this->subject_ids ?? [])->get();
    }


}
