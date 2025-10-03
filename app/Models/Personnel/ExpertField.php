<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Personnel;

use App\Models\BaseModel;
use App\Models\Personnel\Personnel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ExpertField
 * 
 * @property int $id
 * @property string $name
 * @property int $status
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * 
 * @property User|null $user
 * @property Collection|Personnel[] $personnels
 *
 * @package App\Models
 */
class ExpertField extends Model
{
	protected $table = 'expert_fields';

	protected $casts = [
		'status' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'name',
		'status',
		'description',
		'created_by',
		'updated_by'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'updated_by');
	}

	public function personnels()
	{
		return $this->hasMany(Personnel::class, 'expert_field_id');
	}
}
