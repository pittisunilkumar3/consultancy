<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAcademicInfo extends Model
{
    protected $fillable = [
        'user_id',
        'student_service_order_id',
        'certificate_type_id',
        'institution',
        'academic_year',
        'passing_year',
        'result',
        'attachment',
    ];

    public function certificate_type()
    {
        return $this->belongsTo(CertificateType::class);
    }
}
