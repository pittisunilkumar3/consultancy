<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentServiceOrderTask extends Model
{
    use SoftDeletes;

    protected $table = 'student_service_order_tasks'; // Define table name explicitly

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_service_order_id',
        'taskID',
        'task_name',
        'description',
        'start_date',
        'end_date',
        'progress',
        'priority',
        'student_access',
        'created_by',
        'last_reply_id',
        'last_reply_by',
        'last_reply_time',
        'status',
    ];

    /**
     * Define relationship with StudentServiceOrder.
     */
    public function studentServiceOrder()
    {
        return $this->belongsTo(StudentServiceOrder::class, 'student_service_order_id');
    }

    /**
     * Define relationship with the creator (optional).
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Define relationship for last reply user (optional).
     */
    public function lastReplyUser()
    {
        return $this->belongsTo(User::class, 'last_reply_by');
    }


    public function conversations()
    {
        return $this->hasMany(OrderTaskConversation::class);
    }

    public function assignees()
    {
        return $this->hasMany(StudentServiceOrderTaskAssignee::class, 'order_task_id');
    }

    public function attachments()
    {
        return $this->hasMany(StudentServiceOrderTaskAttachment::class, 'order_task_id');
    }

    public function order()
    {
        return $this->belongsTo(StudentServiceOrder::class, 'student_service_order_id');
    }

    public function labels()
    {
        return $this->belongsToMany(Label::class, 'label_order_task');
    }
}
