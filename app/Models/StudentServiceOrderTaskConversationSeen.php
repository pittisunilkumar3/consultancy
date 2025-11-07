<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentServiceOrderTaskConversationSeen extends Model
{
    protected $table = 'student_service_order_task_conversation_seens'; // Define table name explicitly

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_task_id',
        'order_task_conversation_id',
        'is_seen',
        'created_by',
    ];

    /**
     * Define relationship with StudentServiceOrderTask.
     */
    public function task()
    {
        return $this->belongsTo(StudentServiceOrderTask::class, 'order_task_id');
    }

    /**
     * Define relationship with Task Conversation.
     */
    public function conversation()
    {
        return $this->belongsTo(StudentServiceOrderTaskConversation::class, 'order_task_conversation_id');
    }

    /**
     * Define relationship with User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
