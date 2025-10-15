<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'announcement_id',
        'user_id',
        'parent_id',
        'content',
        'is_deleted',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_deleted' => 'boolean',
    ];

    /**
     * Get the announcement that the comment belongs to
     */
    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }

    /**
     * Get the user that created the comment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent comment (for nested replies)
     */
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Get all child comments (replies) with nested children
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')
            ->with(['replies', 'user:id,name,email'])
            ->latest();
    }

    /**
     * Scope to get only parent comments (top-level)
     */
    public function scopeParentOnly($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope to get comments for a specific announcement
     */
    public function scopeForAnnouncement($query, $announcementId)
    {
        return $query->where('announcement_id', $announcementId);
    }

    /**
     * Scope to order by latest
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Get total number of replies recursively
     */
    public function getTotalRepliesAttribute()
    {
        $count = $this->replies->count();
        foreach ($this->replies as $reply) {
            $count += $reply->total_replies;
        }
        return $count;
    }
}
