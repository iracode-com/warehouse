<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Personnel;

use App\Models\BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PersonnelFile
 * 
 * @property int $id
 * @property int $personnel_id
 * @property int $personnel_file_type_id
 * @property string $file_path
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property PersonnelFileType $personnel_file_type
 * @property Personnel $personnel
 *
 * @package App\Models
 */
class PersonnelFile extends Model
{
	protected $table = 'personnel_files';

	protected $casts = [
		'personnel_id' => 'int',
		'personnel_file_type_id' => 'int'
	];

	protected $fillable = [
		'personnel_id',
		'personnel_file_type_id',
		'file_path'
	];

	public function personnel_file_type()
	{
		return $this->belongsTo(PersonnelFileType::class);
	}

	public function personnel()
	{
		return $this->belongsTo(Personnel::class, 'personnel_id');
	}
}
