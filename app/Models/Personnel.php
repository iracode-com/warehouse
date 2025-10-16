<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Personnel extends Model
{
    /** @use HasFactory<\Database\Factories\PersonnelFactory> */
    use HasFactory;

    // Gender constants
    const GENDER_MALE = 'male';

    const GENDER_FEMALE = 'female';

    // Marital status constants
    const MARITAL_SINGLE = 'single';

    const MARITAL_MARRIED = 'married';

    const MARITAL_DIVORCED = 'divorced';

    const MARITAL_WIDOWED = 'widowed';

    // Nationality constants
    const IS_IRANIAN = 1;

    const IS_FOREIGN = 2;

    // Job field constants
    const JOB_WAREHOUSE_MANAGER = 'warehouse_manager';

    const JOB_WAREHOUSE_KEEPER = 'warehouse_keeper';

    const JOB_WAREHOUSE_OPERATOR = 'warehouse_operator';

    const JOB_WAREHOUSE_SUPERVISOR = 'warehouse_supervisor';

    /**
     * Get gender options for select fields
     */
    public static function getGenderOptions(): array
    {
        return [
            self::GENDER_MALE => 'مرد',
            self::GENDER_FEMALE => 'زن',
        ];
    }

    /**
     * Get marital status options for select fields
     */
    public static function getMaritalStatusOptions(): array
    {
        return [
            self::MARITAL_SINGLE => 'مجرد',
            self::MARITAL_MARRIED => 'متاهل',
            self::MARITAL_DIVORCED => 'مطلقه',
            self::MARITAL_WIDOWED => 'بیوه',
        ];
    }

    /**
     * Get nationality options for select fields
     */
    public static function getNationalityOptions(): array
    {
        return [
            self::IS_IRANIAN => 'ایرانی',
            self::IS_FOREIGN => 'غیرایرانی',
        ];
    }

    /**
     * Get job field options for select fields
     */
    public static function getJobFieldOptions(): array
    {
        return [
            self::JOB_WAREHOUSE_MANAGER => 'مدیر انبار',
            self::JOB_WAREHOUSE_KEEPER => 'نگهبان انبار',
            self::JOB_WAREHOUSE_OPERATOR => 'اپراتور انبار',
            self::JOB_WAREHOUSE_SUPERVISOR => 'سرپرست انبار',
        ];
    }

    protected $fillable = [
        'name',
        'family',
        'full_name',
        'fathername',
        'gender',
        'marital_status',
        'personnel_code',
        'national_code',
        'identity_code',
        'sodoor_city_id',
        'is_iranian',
        'passport_number',
        'nationality_country_id',
        'birth_date',
        'mobile',
        'phone',
        'email',
        'country_id',
        'province_id',
        'city_id',
        'address',
        'start_hire_date',
        'end_hire_date',
        'position_id',
        'employment_type_id',
        'cooperation_type_id',
        'expert_field',
        'resume_file',
        'eoc_membership',
        'education_degree_id',
        'education_field_id',
        'user_id',
        'status',
        'structure_id',
        'job_field',
        'bale_chat_id',
        'prefers_sms',
        'prefers_bale',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'start_hire_date' => 'datetime',
            'end_hire_date' => 'datetime',
            'eoc_membership' => 'boolean',
            'status' => 'boolean',
            'prefers_sms' => 'boolean',
            'prefers_bale' => 'boolean',
        ];
    }

    public function getGenderLabelAttribute(): string
    {
        return match ($this->gender) {
            self::GENDER_MALE => 'مرد',
            self::GENDER_FEMALE => 'زن',
            default => $this->gender,
        };
    }

    public function getMaritalStatusLabelAttribute(): string
    {
        return match ($this->marital_status) {
            self::MARITAL_SINGLE => 'مجرد',
            self::MARITAL_MARRIED => 'متاهل',
            self::MARITAL_DIVORCED => 'مطلقه',
            self::MARITAL_WIDOWED => 'بیوه',
            default => $this->marital_status,
        };
    }

    public function getNationalityLabelAttribute(): string
    {
        return match ($this->is_iranian) {
            self::IS_IRANIAN => 'ایرانی',
            self::IS_FOREIGN => 'غیرایرانی',
            default => $this->is_iranian,
        };
    }

    public function getJobFieldLabelAttribute(): string
    {
        return match ($this->job_field) {
            self::JOB_WAREHOUSE_MANAGER => 'مدیر انبار',
            self::JOB_WAREHOUSE_KEEPER => 'نگهبان انبار',
            self::JOB_WAREHOUSE_OPERATOR => 'اپراتور انبار',
            self::JOB_WAREHOUSE_SUPERVISOR => 'سرپرست انبار',
            default => $this->job_field,
        };
    }

    public function getFullNameAttribute(): string
    {
        return $this->name.' '.$this->family;
    }

    public function sodoorCity(): BelongsTo
    {
        return $this->belongsTo(City::class, 'sodoor_city_id');
    }

    public function nationalityCountry(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'nationality_country_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function employmentType(): BelongsTo
    {
        return $this->belongsTo(EmploymentType::class, 'employment_type_id');
    }

    public function cooperationType(): BelongsTo
    {
        return $this->belongsTo(CooperationType::class, 'cooperation_type_id');
    }

    public function educationDegree(): BelongsTo
    {
        return $this->belongsTo(EducationDegree::class, 'education_degree_id');
    }

    public function educationField(): BelongsTo
    {
        return $this->belongsTo(EducationField::class, 'education_field_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function structure(): BelongsTo
    {
        return $this->belongsTo(OrganizationalStructure::class, 'structure_id');
    }

    public function contactInformations(): HasMany
    {
        return $this->hasMany(PersonnelContactInformation::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(PersonnelFile::class);
    }

    public function warehouses(): HasMany
    {
        return $this->hasMany(Warehouse::class, 'keeper_id');
    }

    /**
     * Get the warehouses this personnel works in.
     */
    public function assignedWarehouses(): BelongsToMany
    {
        return $this->belongsToMany(Warehouse::class, 'warehouse_personnel')
            ->withPivot('role', 'is_active')
            ->withTimestamps();
    }

    /**
     * Get active assigned warehouses for this personnel.
     */
    public function activeAssignedWarehouses(): BelongsToMany
    {
        return $this->assignedWarehouses()->wherePivot('is_active', true);
    }
}
