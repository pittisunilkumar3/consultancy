<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentServiceOrderAssignee extends Model
{
    use SoftDeletes;

    protected $table = 'student_service_order_assignees'; // Define table name explicitly

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_service_order_id',
        'assigned_to',
        'assigned_by',
        'is_active',
    ];

    /**
     * Define relationship with StudentServiceOrder.
     */
    public function studentServiceOrder()
    {
        return $this->belongsTo(StudentServiceOrder::class, 'student_service_order_id');
    }

    /**
     * Define relationship with the assigned user.
     */
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Define relationship with the assigning user.
     */
    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
