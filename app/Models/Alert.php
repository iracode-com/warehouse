<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alert extends Model
{
    use HasFactory;

    // Alert type constants
    public const ALERT_TYPE_INFO = 'info';
    public const ALERT_TYPE_WARNING = 'warning';
    public const ALERT_TYPE_ERROR = 'error';
    public const ALERT_TYPE_CRITICAL = 'critical';

    // Status constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_SENT = 'sent';
    public const STATUS_ACKNOWLEDGED = 'acknowledged';
    public const STATUS_RESOLVED = 'resolved';
    public const STATUS_DISMISSED = 'dismissed';

    protected $fillable = [
        'rule_id',
        'category_id',
        'attribute_id',
        'title',
        'message',
        'alert_type',
        'status',
        'trigger_data',
        'recipients',
        'sent_at',
        'acknowledged_at',
        'resolved_at',
        'acknowledged_by',
        'resolved_by',
        'resolution_notes',
        'priority',
        'is_read',
    ];

    protected function casts(): array
    {
        return [
            'trigger_data' => 'array',
            'recipients' => 'array',
            'sent_at' => 'datetime',
            'acknowledged_at' => 'datetime',
            'resolved_at' => 'datetime',
            'priority' => 'integer',
            'is_read' => 'boolean',
        ];
    }

    public function getAlertTypeLabelAttribute(): string
    {
        return match($this->alert_type) {
            self::ALERT_TYPE_INFO => 'اطلاعاتی',
            self::ALERT_TYPE_WARNING => 'هشدار',
            self::ALERT_TYPE_ERROR => 'خطا',
            self::ALERT_TYPE_CRITICAL => 'بحرانی',
            default => $this->alert_type,
        };
    }

    public function getAlertTypeColorAttribute(): string
    {
        return match($this->alert_type) {
            self::ALERT_TYPE_INFO => 'blue',
            self::ALERT_TYPE_WARNING => 'yellow',
            self::ALERT_TYPE_ERROR => 'orange',
            self::ALERT_TYPE_CRITICAL => 'red',
            default => 'gray',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'در انتظار',
            self::STATUS_SENT => 'ارسال شده',
            self::STATUS_ACKNOWLEDGED => 'تایید شده',
            self::STATUS_RESOLVED => 'حل شده',
            self::STATUS_DISMISSED => 'رد شده',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'gray',
            self::STATUS_SENT => 'blue',
            self::STATUS_ACKNOWLEDGED => 'yellow',
            self::STATUS_RESOLVED => 'green',
            self::STATUS_DISMISSED => 'red',
            default => 'gray',
        };
    }

    // Static methods for options
    public static function getAlertTypeOptions(): array
    {
        return [
            self::ALERT_TYPE_INFO => 'اطلاعاتی',
            self::ALERT_TYPE_WARNING => 'هشدار',
            self::ALERT_TYPE_ERROR => 'خطا',
            self::ALERT_TYPE_CRITICAL => 'بحرانی',
        ];
    }

    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_PENDING => 'در انتظار',
            self::STATUS_SENT => 'ارسال شده',
            self::STATUS_ACKNOWLEDGED => 'تایید شده',
            self::STATUS_RESOLVED => 'حل شده',
            self::STATUS_DISMISSED => 'رد شده',
        ];
    }

    public function getPriorityLabelAttribute(): string
    {
        return match($this->priority) {
            1 => 'خیلی کم',
            2 => 'کم',
            3 => 'متوسط',
            4 => 'بالا',
            5 => 'خیلی بالا',
            6 => 'فوری',
            7 => 'بحرانی',
            8 => 'اضطراری',
            9 => 'فوق‌العاده',
            10 => 'بحرانی مطلق',
            default => 'نامشخص',
        };
    }

    // Relationships
    public function rule(): BelongsTo
    {
        return $this->belongsTo(Rule::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(CategoryAttribute::class, 'attribute_id');
    }

    public function acknowledgedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'acknowledged_by');
    }

    public function resolvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    // Helper methods
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isSent(): bool
    {
        return $this->status === 'sent';
    }

    public function isAcknowledged(): bool
    {
        return $this->status === 'acknowledged';
    }

    public function isResolved(): bool
    {
        return $this->status === 'resolved';
    }

    public function isDismissed(): bool
    {
        return $this->status === 'dismissed';
    }

    public function isActive(): bool
    {
        return in_array($this->status, ['pending', 'sent', 'acknowledged']);
    }

    public function isClosed(): bool
    {
        return in_array($this->status, ['resolved', 'dismissed']);
    }

    public function isHighPriority(): bool
    {
        return $this->priority >= 7;
    }

    public function isCritical(): bool
    {
        return $this->priority >= 9;
    }

    public function getTriggerDataArray(): array
    {
        return $this->trigger_data ?? [];
    }

    public function getRecipientsArray(): array
    {
        return $this->recipients ?? [];
    }

    public function getDurationAttribute(): ?int
    {
        if ($this->isResolved() && $this->resolved_at) {
            return $this->created_at->diffInMinutes($this->resolved_at);
        }

        if ($this->isAcknowledged() && $this->acknowledged_at) {
            return $this->created_at->diffInMinutes($this->acknowledged_at);
        }

        return $this->created_at->diffInMinutes(now());
    }

    public function getAgeAttribute(): int
    {
        return $this->created_at->diffInMinutes(now());
    }

    public function markAsSent(): void
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    public function acknowledge(User $user): void
    {
        $this->update([
            'status' => 'acknowledged',
            'acknowledged_at' => now(),
            'acknowledged_by' => $user->id,
        ]);
    }

    public function resolve(User $user, string $notes = null): void
    {
        $this->update([
            'status' => 'resolved',
            'resolved_at' => now(),
            'resolved_by' => $user->id,
            'resolution_notes' => $notes,
        ]);
    }

    public function dismiss(User $user, string $reason = null): void
    {
        $this->update([
            'status' => 'dismissed',
            'resolved_at' => now(),
            'resolved_by' => $user->id,
            'resolution_notes' => $reason,
        ]);
    }

    public function markAsRead(): void
    {
        $this->update(['is_read' => true]);
    }

    public function markAsUnread(): void
    {
        $this->update(['is_read' => false]);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'sent', 'acknowledged']);
    }

    public function scopeClosed($query)
    {
        return $query->whereIn('status', ['resolved', 'dismissed']);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('alert_type', $type);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPriority($query, int $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeHighPriority($query)
    {
        return $query->where('priority', '>=', 7);
    }

    public function scopeCritical($query)
    {
        return $query->where('priority', '>=', 9);
    }

    public function scopeRecent($query, int $hours = 24)
    {
        return $query->where('created_at', '>=', now()->subHours($hours));
    }

    public function scopeOrderedByPriority($query)
    {
        return $query->orderBy('priority', 'desc')->orderBy('created_at', 'desc');
    }

    public function scopeOrderedByAge($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
