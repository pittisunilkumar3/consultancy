<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Program extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'top_section',
        'step_section',
        'created_by',
        'status',
    ];

    protected $casts = [
        'top_section' => 'array',
        'step_section' => 'array'
    ];

    /**
     * Get the user who created the program.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
