<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Location\Province;
use App\Models\Location\City;
use App\Models\Location\Town;
use App\Models\Location\Village;
use App\Models\Location\Zone;
use App\Models\WarehouseUsageType;

class Warehouse extends Model
{
    /** @use HasFactory<\Database\Factories\WarehouseFactory> */
    use HasFactory;

    // Status constants
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    // Approved grade constants
    const GRADE_1 = '1';
    const GRADE_2 = '2';
    const GRADE_3 = '3';
    const GRADE_SPECIAL = 'special';

    /**
     * Get status options for select fields
     */
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_ACTIVE => 'فعال',
            self::STATUS_INACTIVE => 'غیرفعال',
        ];
    }

    /**
     * Get approved grade options for select fields
     */
    public static function getApprovedGradeOptions(): array
    {
        return [
            self::GRADE_1 => 'درجه 1',
            self::GRADE_2 => 'درجه 2',
            self::GRADE_3 => 'درجه 3',
            self::GRADE_SPECIAL => 'ویژه',
        ];
    }

    protected $fillable = [
        'shed_id',
        'branch_id',
        'province_id',
        'city_id',
        'town_id',
        'village_id',
        'title',
        'status',
        'manager_name',
        'manager_phone',
        'usage_type',
        'province',
        'branch',
        'base',
        'warehouse_info',
        'establishment_year',
        'construction_year',
        'population_census',
        'ownership_type',
        'area',
        'under_construction_area',
        'structure_type',
        'warehouse_count',
        'small_inventory_count',
        'large_inventory_count',
        'diesel_forklift_status',
        'gasoline_forklift_status',
        'gas_forklift_status',
        'forklift_standard',
        'ramp_length',
        'ramp_height',
        'warehouse_insurance',
        'building_insurance',
        'fire_suppression_system',
        'fire_alarm_system',
        'ram_rack',
        'ram_rack_count',
        'cctv_system',
        'lighting_system',
        'telephone',
        'longitude',
        'latitude',
        'longitude_e',
        'latitude_n',
        'altitude',
        'address',
        'branch_establishment_year',
        'population_census_1395',
        'provincial_risk_percentage',
        'approved_grade',
        'natural_hazards',
        'urban_location',
        'main_road_access',
        'heavy_vehicle_access',
        'terminal_proximity',
        'parking_facilities',
        'utilities',
        'neighboring_organizations',
        'warehouse_area',
        'gps_x',
        'gps_y',
        'keeper_id',
        'postal_address',
        'nearest_branch_1_id',
        'distance_to_branch_1',
        'nearest_branch_2_id',
        'distance_to_branch_2',
    ];

    protected function casts(): array
    {
        return [
            'area' => 'decimal:2',
            'under_construction_area' => 'decimal:2',
            'ramp_length' => 'decimal:2',
            'ramp_height' => 'decimal:2',
            'longitude' => 'decimal:7',
            'latitude' => 'decimal:7',
            'longitude_e' => 'decimal:7',
            'latitude_n' => 'decimal:7',
            'altitude' => 'decimal:2',
            'provincial_risk_percentage' => 'decimal:2',
            'natural_hazards' => 'array',
            'terminal_proximity' => 'array',
            'utilities' => 'array',
            'neighboring_organizations' => 'array',
            'warehouse_area' => 'decimal:2',
            'gps_x' => 'decimal:7',
            'gps_y' => 'decimal:7',
            'distance_to_branch_1' => 'decimal:2',
            'distance_to_branch_2' => 'decimal:2',
        ];
    }

    public function getUsageTypeLabelAttribute(): string
    {
        return match($this->usage_type) {
            'emergency' => 'امدادی',
            'scrap_used' => 'اسقاط و مستعمل (غیرامدادی)',
            'auto_parts' => 'لوازم و قطعات یدکی خودرو',
            'ready_operations' => 'آماده عملیات',
            'air_rescue_parts' => 'لوازم و قطعات امداد هوایی',
            'rescue_equipment' => 'تجهیزات امداد و نجات',
            'temporary' => 'موقت',
            default => $this->usage_type,
        };
    }

    public function getOwnershipTypeLabelAttribute(): string
    {
        return match($this->ownership_type) {
            'owned' => 'مالکیتی',
            'rented' => 'استیجاری',
            'donated' => 'اهدا',
            default => $this->ownership_type,
        };
    }

    public function getStructureTypeLabelAttribute(): string
    {
        return match($this->structure_type) {
            'concrete' => 'بتنی',
            'metal' => 'فلزی',
            'prefabricated' => 'پیش‌ساخته',
            default => $this->structure_type,
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_ACTIVE => 'فعال',
            self::STATUS_INACTIVE => 'غیرفعال',
            default => $this->status,
        };
    }

    public function getApprovedGradeLabelAttribute(): string
    {
        return match($this->approved_grade) {
            self::GRADE_1 => 'درجه 1',
            self::GRADE_2 => 'درجه 2',
            self::GRADE_3 => 'درجه 3',
            self::GRADE_SPECIAL => 'ویژه',
            default => $this->approved_grade,
        };
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function town(): BelongsTo
    {
        return $this->belongsTo(Town::class);
    }

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }

    /**
     * Get the warehouse keeper for this warehouse.
     */
    public function keeper(): BelongsTo
    {
        return $this->belongsTo(Personnel::class, 'keeper_id');
    }

    /**
     * Get the zones for this warehouse.
     */
    public function zones(): HasMany
    {
        return $this->hasMany(Zone::class);
    }

    /**
     * Get the usage types for this warehouse.
     */
    public function usageTypes(): HasMany
    {
        return $this->hasMany(WarehouseUsageType::class);
    }

    /**
     * Get usage types as array for form field
     */
    public function getUsageTypesAttribute(): array
    {
        return $this->usageTypes()->pluck('usage_type')->toArray();
    }

    /**
     * Set usage types from array
     */
    public function setUsageTypesAttribute(array $usageTypes): void
    {
        // This will be handled in the resource save method
    }

    /**
     * Get the first nearest branch for this warehouse.
     */
    public function nearestBranch1(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'nearest_branch_1_id');
    }

    /**
     * Get the second nearest branch for this warehouse.
     */
    public function nearestBranch2(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'nearest_branch_2_id');
    }

    /**
     * Get the shed for this warehouse.
     */
    public function shed(): BelongsTo
    {
        return $this->belongsTo(WarehouseShed::class);
    }

    /**
     * Get the items for this warehouse.
     */
    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    /**
     * Get the racks for this warehouse.
     */
    public function racks(): HasMany
    {
        return $this->hasMany(Rack::class);
    }
}
