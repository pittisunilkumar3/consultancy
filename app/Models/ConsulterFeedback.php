<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConsulterFeedback extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'consulter_id',
        'student_id',
        'appointment_id',
        'comment',
        'status',
    ];

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    public function consulter()
    {
        return $this->belongsTo(User::class, 'consulter_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id','id');
    }
}
