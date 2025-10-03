<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Courses;

use App\Models\BaseModel;
use App\Models\Personnel\Personnel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EducationField
 * 
 * @property int $id
 * @property string $name
 * @property int $status
 * @property string $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Personnel[] $personnels
 *
 * @package App\Models
 */
class EducationField extends Model
{
	protected $table = 'education_fields';

	protected $fillable = [
		'name',
		'status',
		'description'
	];

	public function personnels()
	{
		return $this->hasMany(Personnel::class);
	}
}
