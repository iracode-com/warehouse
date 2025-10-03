<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Personnel;

use App\Models\BaseModel;
use App\Models\Personnel\Personnel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CooperationType
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
class CooperationType extends Model
{
	protected $table = 'cooperation_types';

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
