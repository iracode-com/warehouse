<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Personnel;

use App\Models\BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PersonnelContactInformation
 * 
 * @property int $id
 * @property int $personnel_id
 * @property int $type_id
 * @property string $contact
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Personnel $personnel
 * @property PersonnelContactInformationType $personnel_contact_information_type
 *
 * @package App\Models
 */
class PersonnelContactInformation extends Model
{
	protected $table = 'personnel_contact_informations';

	protected $casts = [
		'personnel_id' => 'int',
		'type_id' => 'int'
	];

	protected $fillable = [
		'personnel_id',
		'type_id',
		'contact'
	];

	public function personnel()
	{
		return $this->belongsTo(Personnel::class, 'personnel_id');
	}

	public function personnel_contact_information_type()
	{
		return $this->belongsTo(PersonnelContactInformationType::class, 'type_id');
	}
}
