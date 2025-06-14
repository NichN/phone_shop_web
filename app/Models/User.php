<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    protected $table = 'users';
    protected $fillable = [
        'name',
        'email',
        'password',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'email_verified_at',
        'role_id',
        'phone_number',
        'profile_image',
        'address',
        'two_factor_code', 
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];  

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
