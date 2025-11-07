<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentServiceOrderTaskAttachment extends Model
{
    protected $table = 'student_service_order_task_attachments'; // Define table name explicitly

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_task_id',
        'file',
    ];

    public function orderTask()
    {
        return $this->belongsTo(StudentServiceOrderTask::class, 'order_task_id');
    }

    public function filemanager()
    {
        return $this->belongsTo(FileManager::class, 'file', 'id');
    }
}
