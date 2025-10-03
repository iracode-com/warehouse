<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Courses;

use App\Models\BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EducationalInformationCourse
 * 
 * @property int $id
 * @property int $educational_information_id
 * @property int $course_id
 * @property int $pass_status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Course $course
 * @property EducationalInformation $educational_information
 *
 * @package App\Models
 */
class EducationalInformationCourse extends BaseModel
{
	protected $table = 'educational_information_courses';

	protected $casts = [
		'educational_information_id' => 'int',
		'course_id' => 'int',
		'pass_status' => 'int'
	];

	protected $fillable = [
		'educational_information_id',
		'course_id',
		'pass_status'
	];

	public const PASS_STATUSES = [
		1=>'گذرانده',
		2=>'باقی مانده',
	];

	public function course()
	{
		return $this->belongsTo(Course::class, 'course_id');
	}

	public function educational_information()
	{
		return $this->belongsTo(EducationalInformation::class, 'educational_information_id');
	}
}
