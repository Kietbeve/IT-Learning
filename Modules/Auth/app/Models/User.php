<?php

namespace Modules\Auth\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
        'provider',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}