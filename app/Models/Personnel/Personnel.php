<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Personnel;

use App\Models\BaseModel;
use App\Models\Personnel\CooperationType;
use App\Models\Courses\EducationDegree;
use App\Models\Courses\EducationField;
use App\Models\Personnel\EmploymentType;
use App\Models\Location\City;
use App\Models\Location\Country;
use App\Models\Location\Province;
use App\Models\Organization\OrganizationalStructure;
use App\Models\Organization\Position;
use App\Models\User;
use App\Models\ActivityLocation;
use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * Class Personnel
 *
 * @property int $id
 * @property string $name
 * @property string $family
 * @property string|null $fathername
 * @property string $gender
 * @property string $marital_status
 * @property string|null $national_code
 * @property string|null $identity_code
 * @property string|null $personnel_code
 * @property int $sodoor_city_id
 * @property string $is_iranian
 * @property string|null $passport_number
 * @property int $nationality_country_id
 * @property int $country_id
 * @property int $province_id
 * @property int $city_id
 * @property string|null $address
 * @property string|null $mobile
 * @property string|null $phone
 * @property string|null $email
 * @property Carbon|null $birth_date
 * @property Carbon|null $start_hire_date
 * @property Carbon|null $end_hire_date
 * @property int $position_id
 * @property int $employment_type_id
 * @property int $cooperation_type_id
 * @property int $edu_education_degree_id
 * @property int $edu_education_field_id
 * @property int $user_id
 * @property int $status
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property CooperationType $cooperation_type
 * @property EducationDegree $education_degree
 * @property EducationField $education_field
 * @property EmploymentType $employment_type
 * @property Position $position
 * @property City $city
 * @property Country $country
 * @property Province $province
 * @property User $user
 *
 * @package App\Models
 */
class Personnel extends Model
{
    use Notifiable;
    protected $table = 'personnels';

    protected $casts = [
        'sodoor_city_id' => 'int',
        'nationality_country_id' => 'int',
        'country_id' => 'int',
        'province_id' => 'int',
        'city_id' => 'int',
        'birth_date' => 'datetime',
        'start_hire_date' => 'datetime',
        'end_hire_date' => 'datetime',
        'position_id' => 'int',
        'employment_type_id' => 'int',
        'cooperation_type_id' => 'int',
        'education_degree_id' => 'int',
        'education_field_id' => 'int',
        'user_id' => 'int',
        'status' => 'int'
    ];

    protected $fillable = [
        'name',
        'family',
        'fathername',
        'gender',
        'marital_status',
        'national_code',
        'identity_code',
        'personnel_code',
        'sodoor_city_id',
        'is_iranian',
        'passport_number',
        'nationality_country_id',
        'country_id',
        'province_id',
        'city_id',
        'address',
        'mobile',
        'phone',
        'email',
        'birth_date',
        'start_hire_date',
        'end_hire_date',
        'organizational_structure_id',
        'position_id',
        'employment_type_id',
        'cooperation_type_id',
        'education_degree_id',
        'education_field_id',
        'user_id',
        'status',
        'description',
        'job_field',

        'prefers_bale',
        'prefers_telegram',
        'prefers_sms',
        'telegram_chat_id',
        'bale_chat_id',

    ];

    public function routeNotificationForSms()
    {
        return $this->mobile;
    }

    public function routeNotificationForBale()
    {
        return $this->bale_chat_id;
    }

    public function routeNotificationForTelegram()
    {
        return $this->telegram_chat_id;
    }

    public const GENDERS = [
        1 => 'مرد',
        2 => 'زن',
    ];

    public const MARITAL_STATUSES = [
        1 => 'مجرد',
        2 => 'متاهل',
    ];

    public const IS_IRANIAN = [
        1 => 'ایرانی',
        2 => 'غیر ایرانی',
    ];

    public const JOB_FIELDS = [
        1 => 'مدیریت بحران',
        2 => 'پدافند',
        3 => 'متخصصین پدافند',
    ];

    public const EMPLOYMENT_TYPES = [
        'official' => 'رسمی',
        'contractual' => 'پیمانی',
        'agreement' => 'قراردادی',
        'corporate' => 'شرکتی',
        'relief' => 'امدادگری',
    ];

    public function cooperation_type()
    {
        return $this->belongsTo(CooperationType::class);
    }

    public function education_degree()
    {
        return $this->belongsTo(EducationDegree::class);
    }

    public function education_field()
    {
        return $this->belongsTo(EducationField::class);
    }

    public function employment_type()
    {
        return $this->belongsTo(EmploymentType::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'sodoor_city_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'nationality_country_id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organizational_structure()
    {
        return $this->belongsTo(OrganizationalStructure::class);
    }

    public function personnel_files()
    {
        return $this->hasMany(PersonnelFile::class);
    }

    public function personnel_contact_informations()
    {
        return $this->hasMany(PersonnelContactInformation::class);
    }

    public function activity_locations()
    {
        return $this->belongsToMany(ActivityLocation::class, 'personnel_activity_locations');
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'personnel_courses');
    }
}
