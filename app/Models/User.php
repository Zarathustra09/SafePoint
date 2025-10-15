<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'profile_picture',
        'password',
        'is_verified',
        'valid_id_image'
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_verified' => 'boolean',
        ];
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    public function crimeReports(): HasMany
    {
        return $this->hasMany(CrimeReport::class, 'reported_by');
    }

    public function deviceTokens(): HasMany
    {
        return $this->hasMany(UserDeviceToken::class);
    }

    public function addDeviceToken($token, $deviceType = null, $timestamp = null)
    {
        return $this->deviceTokens()->updateOrCreate(
            ['token' => $token],
            [
                'device_type' => $deviceType,
                'last_used_at' => $timestamp ?? now(),
                'registered_at' => now()
            ]
        );
    }

    public function removeDeviceToken($token)
    {
        return $this->deviceTokens()->where('token', $token)->delete();
    }

    public function updateTokenTimestamp($token)
    {
        return $this->deviceTokens()
                    ->where('token', $token)
                    ->update(['last_used_at' => now()]);
    }

    public function removeStaleTokens()
    {
        $cutoffDate = now()->subDays(30);
        return $this->deviceTokens()->where('last_used_at', '<', $cutoffDate)->delete();
    }

    public function getActiveTokens()
    {
        return $this->deviceTokens()
                    ->where('last_used_at', '>=', now()->subDays(30))
                    ->pluck('token')
                    ->toArray();
    }
}
