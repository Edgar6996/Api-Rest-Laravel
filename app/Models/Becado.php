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
 *
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

        # Para completar el registro, debe tener una foto, y la huella cargada.
        if ($this->foto == null) {
            return  false; // no tiene la foto
        }

        # Verificamos la huella
        $huella = $this->huella()->first();
        if(!$huella) return  false;

        # La huella tiene que tener los binarios
        if ($huella->template_huella == null || $huella->img_huella == null) {
            return false;
        }

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
}
