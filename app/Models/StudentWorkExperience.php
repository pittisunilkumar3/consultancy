<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentWorkExperience extends Model
{
    protected $fillable = [
        'user_id',
        'student_service_order_id',
        'title',
        'company',
        'designation',
        'start_date',
        'end_date',
        'description',
        'attachment',
    ];

}
