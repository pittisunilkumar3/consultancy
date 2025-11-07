<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnboardingFormSetting extends Model
{
    protected $fillable = [
        'field_slug',
        'field_show',
        'field_required',
    ];
}
