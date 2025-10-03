<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }

    // Relationships
    public function personnel(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Personnel::class, 'personnel_activity_locations');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    // Accessors
    public function getStatusLabelAttribute(): string
    {
        return $this->status ? 'فعال' : 'غیرفعال';
    }

    public function getStatusColorAttribute(): string
    {
        return $this->status ? 'success' : 'gray';
    }
}
