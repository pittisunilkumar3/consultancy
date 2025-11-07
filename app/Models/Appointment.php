<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'appointment_ID',
        'user_id',
        'consulter_id',
        'consultation_slot_id',
        'date',
        'consultation_type',
        'consultation_link',
        'narration',
        'meeting_provider_id',
        'status',
        'payment_status',
        'is_free',
    ];

    public static function boot()
    {
        parent::boot();

        static::created(function ($appointment) {
            $prefix = strtoupper(substr(getOption('app_name', 'ZAI'), 0, 3)).'AP';
            $appointment->appointment_ID = $prefix . str_pad($appointment->id, 6, '0', STR_PAD_LEFT);
            $appointment->save();
        });
    }

    public function meeting_provider()
    {
        return $this->belongsTo(MeetingPlatform::class, 'meeting_provider_id');
    }

    public function slot()
    {
        return $this->belongsTo(ConsultationSlot::class, 'consultation_slot_id');
    }

    public function consulter()
    {
        return $this->belongsTo(User::class, 'consulter_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function payments(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }

    public function payment()
    {
        return $this->morphOne(Payment::class, 'paymentable')
            ->where(function ($query) {
                $query->where('payment_status', PAYMENT_STATUS_PAID)
                    ->orWhere(function ($query) {
                        $query->whereIn('gateway_id', function ($query) {
                            $query->select('id')
                                ->from('gateways')
                                ->whereIn('slug', ['bank', 'cash']); // Assuming 'bank' and 'cash' are the slugs for bank/cash payments
                        })
                            ->whereIn('payment_status', [PAYMENT_STATUS_PENDING, PAYMENT_STATUS_PAID]);
                    });
            })
            ->orderBy('created_at')
            ->latestOfMany(); // Ensures you get the latest one if there are multiple
    }

}
