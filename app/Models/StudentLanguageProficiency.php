<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentLanguageProficiency extends Model
{
    protected $fillable = [
        'user_id',
        'student_service_order_id',
        'language_proficiency_test_id',
        'score',
        'test_date',
        'expired_date',
        'attachment',
    ];

    public function test()
    {
        return $this->belongsTo(LanguageProficiencyTest::class, 'language_proficiency_test_id');

    }

}
