<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Personnel;

use App\Models\BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PersonnelFileType
 * 
 * @property int $id
 * @property string $name
 * @property int $status
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|PersonnelFile[] $personnel_files
 *
 * @package App\Models
 */
class PersonnelFileType extends Model
{
	protected $table = 'personnel_file_types';

	protected $fillable = [
		'name',
		'status',
		'description'
	];

	public function personnel_files()
	{
		return $this->hasMany(PersonnelFile::class);
	}
}
