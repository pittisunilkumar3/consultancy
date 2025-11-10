<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CareerCornerSubmission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'form_structure_id',
        'form_data',
        'status',
        'notes',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'form_data' => 'array',
        'status' => 'integer',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Get the user (student) who submitted the form
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the form structure this submission belongs to
     */
    public function formStructure()
    {
        return $this->belongsTo(FormStructure::class, 'form_structure_id');
    }

    /**
     * Get the admin who reviewed this submission
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
