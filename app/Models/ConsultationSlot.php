<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class ConsultationSlot extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'start_time',
        'end_time',
        'status'
    ];

    /**
     * Get the appointments for this consultation slot.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'consultation_slot_id');
    }

    /**
     * Get the duration of the slot in minutes.
     *
     * @return int
     */
    public function getDurationAttribute()
    {
        $start = Carbon::parse($this->start_time);
        $end = Carbon::parse($this->end_time);

        return $end->diffInMinutes($start);
    }
}
