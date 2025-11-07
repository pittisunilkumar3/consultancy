<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    protected $fillable = [
        'name',
        'slug'
    ];

    public function orderTasks()
    {
        return $this->belongsToMany(OrderTask::class, 'label_order_task');
    }
}
