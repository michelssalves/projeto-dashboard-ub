<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'config_users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'access_level',
        'api_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
