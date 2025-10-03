<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Courses;

use App\Models\BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class JobField
 * 
 * @property int $id
 * @property string $name
 * @property int $status
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|CoursesJobField[] $courses_job_fields
 * @property Collection|EducationalInformation[] $educational_informations
 *
 * @package App\Models
 */
class JobField extends Model
{
	protected $table = 'job_fields';

	protected $casts = [
		'status' => 'int'
	];

	protected $fillable = [
		'name',
		'status',
		'description'
	];

	public function courses_job_fields()
	{
		return $this->hasMany(CoursesJobField::class);
	}

	public function educational_informations()
	{
		return $this->hasMany(EducationalInformation::class, 'job_field_id');
	}
}
