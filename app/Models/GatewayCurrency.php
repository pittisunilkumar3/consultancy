<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GatewayCurrency extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['gateway_id', 'currency', 'conversion_rate', 'user_id', 'tenant_id'];

    public function  getSymbolAttribute()
    {
        return getCurrency($this->currency, true);
    }
}
