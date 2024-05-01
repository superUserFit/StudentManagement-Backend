<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory;

    protected $table = 'users', $guarded = [];

    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'profile_image',
        'email',
        'role',
        'password'
    ];

    protected $hidden = [
        'password'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected static function boot() {
        parent::boot();

        static::creating(function ($user) {
            $user->id = Str::uuid();
        });
    }

    public function getEmailForVerification() {}

    public function sendEmailVerificationNotification() {}

    public function hasVerifiedEmail() {}

    public function markEmailAsVerified() {}
}
