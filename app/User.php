<?php

namespace App;

use App\Models\Felhasznalok;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','bejelentkezesi_nev', 'email', 'password', 'google2fa_secret'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'google2fa_secret',
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
/*
    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getPasswordAttribute($val)
    {
        return $this->password;
    }
    public function setPasswordAttribute($val)
    {
        return $this->password=$val;
    }

    public function getAuthIdentifier()
    {
        return $this->id;
    }

    public function getIdAttribute()
    {
        return $this->id;
    }

    public function toFelhasznalo() : User
    {
        return User::find($this->getAuthIdentifier());
    }*/
}
