<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'duration_hours',
        'instructor',
        'institution',
        'completion_date',
        'certificate_number',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'duration_hours' => 'integer',
            'completion_date' => 'date',
            'status' => 'boolean',
        ];
    }

    // Relationships
    public function personnel(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Personnel::class, 'personnel_courses');
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
