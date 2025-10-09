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
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
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

    /**
     * Get the announcements for the user.
     */
    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    public function crimeReports(): HasMany
    {
        return $this->hasMany(CrimeReport::class, 'reported_by');
    }

    // Add this to your existing User model
    public function deviceTokens()
    {
        return $this->hasMany(UserDeviceToken::class);
    }

    public function addDeviceToken($token, $deviceType = null)
    {
        return $this->deviceTokens()->updateOrCreate(
            ['token' => $token],
            ['device_type' => $deviceType]
        );
    }

    public function removeDeviceToken($token)
    {
        return $this->deviceTokens()->where('token', $token)->delete();
    }

}
