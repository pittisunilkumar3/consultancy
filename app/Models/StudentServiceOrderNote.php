<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentServiceOrderNote extends Model
{
    use SoftDeletes;

    protected $table = 'student_service_order_notes'; // Define table name explicitly

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_service_order_id',
        'details',
        'user_id',
    ];

    /**
     * Define relationship with StudentServiceOrder.
     */
    public function studentServiceOrder()
    {
        return $this->belongsTo(StudentServiceOrder::class, 'student_service_order_id');
    }

    /**
     * Define relationship with User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
