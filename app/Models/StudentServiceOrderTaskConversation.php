<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentServiceOrderTaskConversation extends Model
{
    use SoftDeletes;

    protected $table = 'student_service_order_task_conversations'; // Define table name explicitly

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_task_id',
        'user_id',
        'conversation_text',
        'attachment',
        'type',
    ];

    /**
     * Define relationship with StudentServiceOrderTask.
     */
    public function task()
    {
        return $this->belongsTo(StudentServiceOrderTask::class, 'order_task_id');
    }

    /**
     * Define relationship with User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
