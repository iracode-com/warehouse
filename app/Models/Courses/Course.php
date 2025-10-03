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
 * Class Course
 * 
 * @property int $id
 * @property string $name
 * @property string $code
 * @property int $teaching_hours
 * @property int $field
 * @property int $course_type
 * @property int $job_type
 * @property string|null $form_file
 * @property int $status
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|CoursesJobField[] $courses_job_fields
 * @property Collection|EducationalInformationCourse[] $educational_information_courses
 *
 * @package App\Models
 */
class Course extends Model
{
	protected $table = 'courses';

	protected $casts = [
		'teaching_hours' => 'int',
		'field' => 'int',
		'course_type' => 'int',
		'job_type' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'name',
		'code',
		'teaching_hours',
		'field',
		'course_type',
		'job_type',
		'form_file',
		'status',
		'description'
	];

	public const COURSE_TYPES = [
		1 => 'عمومی',
		2 => 'تخصصی',
	];

	public const FIELD_TYPES = [
		1 => 'مدیریت بحران',
		2 => 'پدافند غیرعامل',
	];

	public const JOB_TYPES = [
		1 => 'عمومی',
		2 => 'کارشناسی',
		3 => 'مدیریتی',
	];

	public function course_job_fields()
	{
		return $this->hasMany(CourseJobField::class);
	}

	public function educational_information_courses()
	{
		return $this->hasMany(EducationalInformationCourse::class, 'course_id');
	}
}
