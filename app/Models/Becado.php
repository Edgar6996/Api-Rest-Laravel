<?php

namespace App\Models;

use App\Enums\CategoriasBecados;
use App\Enums\EstadoBecados;
use App\Models\Calendario;
use App\Models\DetalleDiario;
use App\Models\Huella;
use App\Models\Registro;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Becado
 * @package App\Models
 * @mixin \Eloquent
 */
class Becado extends Model
{
    use HasFactory;

    protected $fillable = [
        'dni', 'nombres', 'apellidos', 'email', 'categoria'
    ];

    #nombre de la tabla que hace referencia
    protected $table = 'becados';

    # Definimos los atributos con valores por defecto
    protected $attributes = [
        'categoria' => CategoriasBecados::BECADO,
        'estado' => EstadoBecados::ACTIVO,
    ];

    # Indicamos los atributos que no se deben enviar al cliente
    protected $hidden = [ ];

    # Nos permite indicar relaciones que se obtengan de forma automatica
    # protected $with = ['calendario'];

    #relaciones

    public function cuenta()
    {
        return $this->hasOne(User::class, 'user_id');
    }

    public function registros(){
        return $this->hasMany(Registro::class, 'becado_id');
    }

    public function calendario(){
        return $this->hasOne(Calendario::class, 'becado_id');
    }

    public function huella(){
        return $this->hasOne(Huella::class, 'becado_id');
    }

    public function detalleDiario(){
        return $this->hasMany(DetalleDiario::class, 'becado_id');
    }




    # Scopes
    public function scopeActivos(Builder $query)
    {
        $query->where('estado', '=', EstadoBecados::ACTIVO);
    }








}
