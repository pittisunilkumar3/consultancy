<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetingPlatform extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'image',
        'type',
        'account_id',
        'created_by',
        'key',
        'secret',
        'token',
        'calender_id',
        'timezone',
        'address',
        'host_video',
        'participant_video',
        'waiting_room',
        'status',
    ];
}
