<?php

namespace App\Models;

use App\Enums\TiposUsuarios;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * Class User
 * @property string password
 * @property string name
 * @property int id
 * @property int|TiposUsuarios rol
 * @package App\Models
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    const USER_LECTOR_EMAIL = 'lector@email.app';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'rol'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'rol' => 'integer'
    ];

    // Relaciones
    public function becado(){
        return $this->hasOne(Becado::class, 'user_id');
    }

    /**
     * Obtiene el usaurio Lector
     * @return \Illuminate\Database\Eloquent\Model|User|null
     */
    public static function getLectorUser()
    {
        $usr =  User::where('email','like',self::USER_LECTOR_EMAIL)->first();
        if(!$usr){
            $usr = self::_registrarUsuarioLector();
        }
        return  $usr;
    }

    private static function _registrarUsuarioLector()
    {
        return User::create([
            'email' => User::USER_LECTOR_EMAIL,
            'name' => 'Lector',
            'username' => 'lector',
            'password' => \Hash::make('*lector*'),
            'rol' => TiposUsuarios::LECTOR_HUELLA,
            'email_verified_at' => now()
        ]);
    }


    public function isAdmin()
    {
        return in_array($this->rol, [
            TiposUsuarios::ADMINISTRADOR,
            TiposUsuarios::ROOT
        ]);
    }

    public function isRoot()
    {
        return $this->rol === TiposUsuarios::ROOT;
    }

    public function isBecado()
    {
        return $this->rol === TiposUsuarios::BECADO;
    }
}

