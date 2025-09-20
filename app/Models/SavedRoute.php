<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavedRoute extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'start_lat',
        'start_lng',
        'end_lat',
        'end_lng',
        'start_address',
        'end_address',
        'polyline',
        'safety_score',
        'duration',
        'distance',
        'crime_analysis',
        'is_safer_route',
        'route_type',
    ];

    protected $casts = [
        'start_lat' => 'decimal:8',
        'start_lng' => 'decimal:8',
        'end_lat' => 'decimal:8',
        'end_lng' => 'decimal:8',
        'safety_score' => 'decimal:2',
        'crime_analysis' => 'array',
        'is_safer_route' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the saved route.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include routes for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include safer routes.
     */
    public function scopeSaferRoutes($query)
    {
        return $query->where('is_safer_route', true);
    }

    /**
     * Scope a query to only include regular routes.
     */
    public function scopeRegularRoutes($query)
    {
        return $query->where('is_safer_route', false);
    }
}
