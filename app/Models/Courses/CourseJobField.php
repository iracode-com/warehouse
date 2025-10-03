<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Courses;

use App\Models\BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CourseJobField
 * 
 * @property int $id
 * @property int $course_id
 * @property int $job_field_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Course $course
 * @property JobField $job_field
 *
 * @package App\Models
 */
class CourseJobField extends Model
{
	protected $table = 'course_job_fields';

	protected $casts = [
		'course_id' => 'int',
		'job_field_id' => 'int'
	];

	protected $fillable = [
		'course_id',
		'job_field_id'
	];

	public function course()
	{
		return $this->belongsTo(Course::class);
	}

	public function job_field()
	{
		return $this->belongsTo(JobField::class);
	}
}
