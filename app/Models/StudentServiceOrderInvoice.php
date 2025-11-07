<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentServiceOrderInvoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'student_id',
        'student_service_order_id',
        'service_id',
        'invoiceID',
        'due_date',
        'details',
        'payable_amount',
        'total',
        'payment_status',
        'created_by',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id')->withTrashed();
    }

    public function service_order()
    {
        return $this->belongsTo(StudentServiceOrder::class, 'student_service_order_id');
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

    public static function boot()
    {
        parent::boot();

        static::created(function ($serviceOrder) {
            $prefix = strtoupper(substr(getOption('app_name', 'ZAI'), 0, 3)).'INV';
            $serviceOrder->invoiceID = $prefix . str_pad($serviceOrder->id, 6, '0', STR_PAD_LEFT);
            $serviceOrder->save();
        });
    }
}
