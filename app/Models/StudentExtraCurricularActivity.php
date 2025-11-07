<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentExtraCurricularActivity extends Model
{
    protected  $table = 'student_extracurricular_activities';

    protected $fillable = [
        'user_id',
        'student_service_order_id',
        'title',
        'description',
        'attachment',
    ];
}
