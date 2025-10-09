<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDeviceToken extends Model
{
    protected $fillable = [
        'user_id',
        'token',
        'device_type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getAllTokens()
    {
        return self::pluck('token')->toArray();
    }

    public static function getTokensByDeviceType($deviceType)
    {
        return self::where('device_type', $deviceType)->pluck('token')->toArray();
    }
}
