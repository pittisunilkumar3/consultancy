<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentServiceOrderFile extends Model
{
    protected $fillable = [
        'student_service_order_id',
        'student_id',
        'name',
        'file',
        'created_by',
    ];

    public function filemanager()
    {
        return $this->belongsTo(FileManager::class, 'file', 'id');
    }
}
