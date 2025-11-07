<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'paymentable_id',
        'paymentable_type',
        'gateway_id',
        'paymentId',
        'tnxId',
        'user_id',
        'bank_id',
        'deposit_slip',
        'sub_total',
        'tax',
        'system_currency',
        'payment_currency',
        'conversion_rate',
        'grand_total',
        'grand_total_with_conversation_rate',
        'subscription_type',
        'payment_details',
        'gateway_callback_details',
        'payment_time',
        'payment_status',
        'paid_by',
    ];

    public function paymentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'payment_id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class );
    }

    public function gateway(){
        return $this->belongsTo(Gateway::class);
    }

    public  function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function paidByUser()
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

}
