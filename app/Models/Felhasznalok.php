<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Felhasznalok
 * 
 * @property int $azonosito
 * @property string $nev
 * @property string $bejelentkezesi_nev
 * @property string $email
 * @property string $jelszo
 * @property string $remember_token
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $google2fa_secret
 * @property string $deleted_at
 * 
 * @property Collection|Jogok[] $jogoks
 * @property Collection|InputAnyagMozgasok[] $input_anyag_mozgasoks
 * @property Collection|Kotesek[] $koteseks
 * @property Collection|SzolgaltatasokFelhasznala[] $szolgaltatasok_felhasznalas
 *
 * @package App\Models
 */
class Felhasznalok extends Model
{
	use SoftDeletes;
	protected $table = 'felhasznalok';
	protected $primaryKey = 'azonosito';

	protected $hidden = [
		'remember_token',
		'google2fa_secret'
	];

	protected $fillable = [
		'nev',
		'bejelentkezesi_nev',
		'email',
		'jelszo',
		'remember_token',
		'google2fa_secret'
	];

	public function jogoks()
	{
		return $this->belongsToMany(Jogok::class, 'felhasznalok_jogok', 'felhasznalo_az', 'jog_az')
					->withPivot('id', 'deleted_at')
					->withTimestamps();
	}

	public function input_anyag_mozgasoks()
	{
		return $this->hasMany(InputAnyagMozgasok::class, 'felhasznalo_az');
	}

	public function koteseks()
	{
		return $this->hasMany(Kotesek::class, 'felhasznalo_az');
	}

	public function szolgaltatasok_felhasznalas()
	{
		return $this->hasMany(SzolgaltatasokFelhasznala::class, 'felhasznalo_rogzito_az');
	}
}
