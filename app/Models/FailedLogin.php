<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FailedLogin extends Model
{
    protected $table = 'failed_logins';

    protected $fillable = [
        'email',
        'ip_address',
        'user_agent',
    ];

    // use default timestamps (created_at will indicate attempt time)
}
