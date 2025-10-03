<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Courses;

use App\Models\BaseModel;
use App\Models\Courses\EducationDegree;
use App\Models\Courses\EducationField;
use App\Models\Organization\Position;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EducationalInformation
 * 
 * @property int $id
 * @property string $national_code
 * @property string $name
 * @property string $family
 * @property int $position_id
 * @property int $job_field_id
 * @property int $education_degree_id
 * @property int $education_field_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property EducationDegree $education_degree
 * @property EducationField $education_field
 * @property JobField $job_field
 * @property Position $position
 * @property Collection|EducationalInformationCourse[] $educational_information_courses
 *
 * @package App\Models
 */
class EducationalInformation extends Model
{
	protected $table = 'educational_informations';

	protected $casts = [
		'position_id' => 'int',
		'job_field_id' => 'int',
		'education_degree_id' => 'int',
		'education_field_id' => 'int'
	];

	protected $fillable = [
		'national_code',
		'name',
		'family',
		'position_id',
		'job_field_id',
		'education_degree_id',
		'education_field_id'
	];

	public function education_degree()
	{
		return $this->belongsTo(EducationDegree::class, 'education_degree_id');
	}

	public function education_field()
	{
		return $this->belongsTo(EducationField::class, 'education_field_id');
	}

	public function job_field()
	{
		return $this->belongsTo(JobField::class, 'job_field_id');
	}

	public function position()
	{
		return $this->belongsTo(Position::class, 'position_id');
	}

	public function educational_information_courses()
	{
		return $this->hasMany(EducationalInformationCourse::class, 'educational_information_id');
	}
}
