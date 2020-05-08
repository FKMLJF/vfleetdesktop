<?php

namespace App;

use App\Models\Felhasznalok;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'felhasznalok';

    protected $primaryKey = 'azonosito';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nev','bejelentkezesi_nev', 'email', 'jelszo', 'google2fa_secret'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'jelszo', 'remember_token', 'google2fa_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Ecrypt the user's google_2fa secret.
     *
     * @param  string  $value
     * @return string
     */
    public function setGoogle2fa_secretAttribute($value)
    {
        $this->attributes['google2fa_secret'] = encrypt($value);
    }

    /**
     * Decrypt the user's google_2fa secret.
     *
     * @param  string  $value
     * @return string
     */
    public function getGoogle2fa_secretAttribute($value)
    {
        clock(__FUNCTION__,$value);
        return decrypt($value);
    }

    public function getAuthPassword()
    {
        return $this->jelszo;
    }

    public function getPasswordAttribute($val)
    {
        return $this->jelszo;
    }
    public function setPasswordAttribute($val)
    {
        return $this->jelszo=$val;
    }

    public function getAuthIdentifier()
    {
        return $this->azonosito;
    }

    public function getIdAttribute()
    {
        return $this->azonosito;
    }

    public function toFelhasznalo() : Felhasznalok
    {
        return Felhasznalok::find($this->getAuthIdentifier());
    }
}
