<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class WarehouseManager extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'national_id',
        'employee_id',
        'birth_date',
        'gender',
        'phone',
        'mobile',
        'email',
        'address',
        'postal_code',
        'hire_date',
        'employment_status',
        'position',
        'department',
        'salary',
        'job_description',
        'warehouse_id',
        'user_id',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'notes',
        'certifications',
        'skills',
        'is_primary_manager',
        'can_approve_orders',
        'can_manage_inventory',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'hire_date' => 'date',
            'salary' => 'decimal:2',
            'certifications' => 'array',
            'skills' => 'array',
            'is_primary_manager' => 'boolean',
            'can_approve_orders' => 'boolean',
            'can_manage_inventory' => 'boolean',
        ];
    }

    /**
     * Get the full name of the warehouse manager.
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get the gender label in Persian.
     */
    public function getGenderLabelAttribute(): string
    {
        return match($this->gender) {
            'male' => 'مرد',
            'female' => 'زن',
            default => $this->gender,
        };
    }

    /**
     * Get the employment status label in Persian.
     */
    public function getEmploymentStatusLabelAttribute(): string
    {
        return match($this->employment_status) {
            'active' => 'فعال',
            'inactive' => 'غیرفعال',
            'terminated' => 'قطع همکاری',
            'retired' => 'بازنشسته',
            default => $this->employment_status,
        };
    }

    /**
     * Get the warehouse that this manager is assigned to.
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get the user account associated with this manager.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get only active managers.
     */
    public function scopeActive($query)
    {
        return $query->where('employment_status', 'active');
    }

    /**
     * Scope to get only primary managers.
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary_manager', true);
    }

    /**
     * Scope to get managers by warehouse.
     */
    public function scopeForWarehouse($query, $warehouseId)
    {
        return $query->where('warehouse_id', $warehouseId);
    }

    /**
     * Check if the manager can approve orders.
     */
    public function canApproveOrders(): bool
    {
        return $this->can_approve_orders && $this->employment_status === 'active';
    }

    /**
     * Check if the manager can manage inventory.
     */
    public function canManageInventory(): bool
    {
        return $this->can_manage_inventory && $this->employment_status === 'active';
    }

    /**
     * Get the age of the manager.
     */
    public function getAgeAttribute(): int
    {
        return Carbon::parse($this->birth_date)->age;
    }

    /**
     * Get the years of service.
     */
    public function getYearsOfServiceAttribute(): int
    {
        return Carbon::parse($this->hire_date)->age;
    }
}
