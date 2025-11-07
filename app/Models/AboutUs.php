<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AboutUs extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'banner_image',
        'details',
        'our_mission_title',
        'our_mission_details',
        'our_mission_image',
        'our_vision_title',
        'our_vision_details',
        'our_vision_image',
        'our_goal_title',
        'our_goal_details',
        'our_goal_image',
        'awards',
        'about_us_point',
        'our_history',
    ];
    protected $casts = [
        'banner_image' => 'array',
        'awards' => 'array',
        'about_us_point' => 'array',
        'our_history' => 'array',
    ];
    public function getAwardsAttribute($value)
    {
        return json_decode($value, true) ?? [];
    }
}
