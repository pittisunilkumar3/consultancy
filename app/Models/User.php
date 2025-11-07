<?php

namespace App\Models;

use App\Mail\EmailNotify;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\URL;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'mobile',
        'email_verified_at',
        'password',
        'gender',
        'day_off',
        'fee',
        'professional_title',
        'experience',
        'about_me',
        'image',
        'role',
        'google_auth_status',
        'google2fa_secret',
        'google_id',
        'facebook_id',
        'verify_token',
        'created_by',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'google2fa_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'day_off' => 'array'
    ];

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function slots()
    {
        return $this->belongsToMany(ConsultationSlot::class, 'consultation_slot_user', 'user_id', 'consultation_slot_id');
    }

    public function reviews()
    {
        return $this->hasMany(ConsulterFeedback::class, 'consulter_id', 'id');
    }


    public function sendEmailVerificationNotification()
    {
        $user = $this;

        $url = URL::temporarySignedRoute(
            'verification.verify', now()->addMinutes(60), ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $viewData = [
            '{{name}}' => $user->name,
            '{{email}}' => $user->email,
            '{{link}}' => $url
        ];
        $data = getEmailTemplate('verify', $viewData);
        Mail::to($user->email)->send(new EmailNotify($data));
    }
}
