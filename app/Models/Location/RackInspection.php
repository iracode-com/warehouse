<?php

namespace App\Models\Location;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class RackInspection extends Model
{
    use HasFactory;

    protected $fillable = [
        'rack_id',
        'inspector_id',
        'inspection_date',
        'safety_status',
        'inspection_notes',
        'issues_found',
        'next_inspection_date',
        'requires_maintenance',
    ];

    protected function casts(): array
    {
        return [
            'inspection_date' => 'date',
            'next_inspection_date' => 'date',
            'issues_found' => 'array',
            'requires_maintenance' => 'boolean',
        ];
    }

    public function getSafetyStatusLabelAttribute(): string
    {
        return match($this->safety_status) {
            'standard' => 'استاندارد',
            'needs_repair' => 'نیاز به تعمیر',
            'critical' => 'بحرانی',
            'out_of_service' => 'خارج از سرویس',
            default => $this->safety_status,
        };
    }

    public function rack(): BelongsTo
    {
        return $this->belongsTo(Rack::class);
    }

    public function inspector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inspector_id');
    }

    public function getIssuesCountAttribute(): int
    {
        return is_array($this->issues_found) ? count($this->issues_found) : 0;
    }

    public function getDaysSinceInspectionAttribute(): int
    {
        return $this->inspection_date->diffInDays(now());
    }

    public function isOverdue(): bool
    {
        if (!$this->next_inspection_date) {
            return false;
        }
        return $this->next_inspection_date->isPast();
    }
}
