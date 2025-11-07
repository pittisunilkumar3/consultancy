<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentServiceOrder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'orderID',
        'student_id',
        'amount',
        'discount',
        'subtotal',
        'total',
        'transaction_amount',
        'payment_status',
        'working_status',
        'created_by',
        'service_id',
        'student_service_order_invoice_id',
        'onboard_status',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id')->withTrashed();
    }

    public function invoice()
    {
        return $this->hasMany(StudentServiceOrderInvoice::class);
    }

    public function assignees()
    {
        return $this->hasMany(StudentServiceOrderAssignee::class, 'student_service_order_id');
    }

    public function notes()
    {
        return $this->hasMany(StudentServiceOrderNote::class, 'student_service_order_id');
    }
    public function documents()
    {
        return $this->hasMany(StudentServiceOrderFile::class, 'student_service_order_id');
    }


    // Relationships

    /**
     * One-to-One relationship with StudentBasicInfo
     */
    public function student_basic_info()
    {
        return $this->hasOne(StudentBasicInfo::class, 'student_service_order_id');
    }

    /**
     * One-to-Many relationship with StudentAcademicInfo
     */
    public function student_academic_info()
    {
        return $this->hasMany(StudentAcademicInfo::class, 'student_service_order_id');
    }

    /**
     * One-to-Many relationship with StudentWorkExperience
     */
    public function student_work_experiences()
    {
        return $this->hasMany(StudentWorkExperience::class, 'student_service_order_id');
    }

    /**
     * One-to-Many relationship with StudentExtraCurricularActivity
     */
    public function student_extra_curriculum_activities()
    {
        return $this->hasMany(StudentExtraCurricularActivity::class, 'student_service_order_id');
    }

    /**
     * One-to-Many relationship with StudentLanguageProficiency
     */
    public function student_language_proficiencies()
    {
        return $this->hasMany(StudentLanguageProficiency::class, 'student_service_order_id');
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($serviceOrder) {
            $prefix = strtoupper(substr(getOption('app_name', 'ZAI'), 0, 3)) . 'ORD';
            $serviceOrder->orderID = $prefix . str_pad($serviceOrder->id, 6, '0', STR_PAD_LEFT);
            $serviceOrder->save();
        });
    }
}
