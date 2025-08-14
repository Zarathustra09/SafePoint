<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrimeReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'severity',
        'latitude',
        'longitude',
        'address',
        'status',
        'incident_date',
        'reported_by',
        'report_image',
    ];

    protected $casts = [
        'incident_date' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function scopeBySeverity($query, string $severity)
    {
        return $query->where('severity', $severity);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeNearLocation($query, float $lat, float $lng, float $radius = 10)
    {
        return $query->whereRaw(
            "6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude))) < ?",
            [$lat, $lng, $lat, $radius]
        );
    }
}
