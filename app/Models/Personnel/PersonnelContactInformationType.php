<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Personnel;

use App\Models\BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ArPersonnelContactInformationType
 * 
 * @property int $id
 * @property string $name
 * @property int $status
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class PersonnelContactInformationType extends Model
{
	protected $table = 'personnel_contact_information_types';

	protected $fillable = [
		'name',
		'status',
		'description'
	];
}
