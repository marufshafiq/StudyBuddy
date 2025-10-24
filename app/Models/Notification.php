<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sender_id',
        'title',
        'message',
        'type',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Get the user that owns the notification.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the sender of the notification.
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Scope a query to only include unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope a query to only include notifications for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Mark the notification as read.
     */
    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Get type badge color.
     */
    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'announcement' => 'blue',
            'task' => 'purple',
            'meeting' => 'green',
            'feedback' => 'yellow',
            'general' => 'gray',
            default => 'gray',
        };
    }

    /**
     * Get type icon.
     */
    public function getTypeIconAttribute(): string
    {
        return match($this->type) {
            'announcement' => '📢',
            'task' => '📝',
            'meeting' => '📅',
            'feedback' => '💬',
            'general' => '🔔',
            default => '🔔',
        };
    }
}
