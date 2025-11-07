<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationSeen extends Model
{
    protected $fillable = [
        'user_id',
        'notification_id',
    ];
}
