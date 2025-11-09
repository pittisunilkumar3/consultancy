<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'question',
        'help_text',
        'type',
        'options',
        'order',
        'required',
    ];

    protected $casts = [
        'options' => 'array',
        'required' => 'boolean',
    ];
}
