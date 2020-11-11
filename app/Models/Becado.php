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
 *
 * @package App\Models
 * @mixin \Eloquent
 *
 *
 * @property int|EstadoBecados estado
 * @property string|null foto: Link de la foto del becado
 * @property int user_id
 */
class Becado extends Model
{
    use HasFactory;

    protected $fillable = [
        'dni', 'nombres', 'apellidos', 'email', 'categoria', 'telefono', 'autorizado_por'
    ];

    #nombre de la tabla que hace referencia
    protected $table = 'becados';

    # Definimos los atributos con valores por defecto
    protected $attributes = [
        'categoria' => CategoriasBecados::BECADO,
        'estado' => EstadoBecados::REGISTRO_INCOMPLETO,
    ];

    # Indicamos los atributos que no se deben enviar al cliente
    protected $hidden = [ ];

    # Nos permite indicar relaciones que se obtengan de forma automatica
    # protected $with = ['calendario'];


    # Getters & Setters
    public function getNombresAttribute()
    {
        return ucwords(mb_strtolower($this->attributes['nombres']));
    }

    public function getApellidosAttribute()
    {
        return ucwords(mb_strtolower($this->attributes['apellidos']));
    }





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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|Huella
     */
    public function huellas(){
        return $this->hasMany(Huella::class, 'becado_id');
    }

    public function detalleDiario(){
        return $this->hasMany(DetalleDiario::class, 'becado_id');
    }

    public function ultimoDetalleDiario(){
        return $this->hasOne(DetalleDiario::class, 'becado_id')->latest();
    }

    # Scopes
    public function scopeActivos(Builder $query)
    {
        $query->where('estado', '=', EstadoBecados::ACTIVO);
    }


    /**
     * Esta funcion se ejecuta cuando se crea una instancia del modelo,
     * similar al constructor de una clase
     */
    protected static function booted()
    {
        # Definimos un Global Scope, un filtro que se aplicara en todas las consultas sobre Becados
        static::addGlobalScope('activos', function (Builder $query) {
            // Por defecto, ignoramos a todos los que estan DESHABILITADOS
            $query->whereIn('estado', [EstadoBecados::ACTIVO, EstadoBecados::REGISTRO_INCOMPLETO]);
        });
    }

    /**
     * Esta funcion verifica si se completó el registro de el becado, cuando está en estado REGISTRO_INCOMPLETO
     *
     * @return bool Indica si se activó el becado o no.
     */
    public function checkRegistroCompletado(): bool
    {
        #. Primero verificamos si ya no está activo
        if($this->estado === EstadoBecados::ACTIVO){
            return  true; // ya esta ACTIVO
        }

        # Para completar el registro, debe tener la huella cargada.
        // la foto ya no es requisito

        # Verificamos las huellas, debe tener dos
        $total = $this->huellas()->count();
        if($total < 2) return  false;

        // Se cumplieron todas las condiciones, actualizamos el estado
        $this->estado = EstadoBecados::ACTIVO;
        return $this->save();
    }


    /**
     * Busca un becado a partir del id de el calendario asociado. Devuelve Null si no lo encuentra.
     *
     * @param $calendario_id
     * @return Becado|Builder|Model|object|null
     */
    public static function findByCalendarioId($calendario_id)
    {
        return Becado::whereHas('calendario', function(Builder $q) use($calendario_id){
            $q->where('id', '=', $calendario_id);
        })->first();
    }

     /**
     * Busca un becado a partir del id de el usuario asociado. Devuelve Null si no lo encuentra.
     *
     * @return Becado|Builder|Model|object|null
     */
    public static function getBecadoActual()
    {
        return Becado::where('user_id', \Auth::id())->first();
    }

     /**
     * Busca una reservaActual del becado a partir de su ID. Devuelve Null si no lo encuentra.
     *
     * @return Becado|Builder|Model|object|null
     */
    public static function reservaActual($becadoId)
    {
        $diarioActual = Diario::diarioActual();

        return $diarioActual->detalleDiario()->where('becado_id', $becadoId)->first();
    }

}
