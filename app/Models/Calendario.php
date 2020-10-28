<?php

namespace App\Models;

use App\Enums\EstadoBecados;
use App\Models\Becado;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Calendario
 * @package App\Models
 * @mixin \Eloquent
 */
class Calendario extends Model
{
    use HasFactory;

    #nombre de la tabla que hace referenciac
    protected $table = 'calendarios';

    protected $guarded  = [
       'id', "created_at", 'updated_at'
    ];

    public function becado(){
    	# Siempre que el modelo actual sea el que tenga la clave foranea, debemos usar belongsTo (Relacion inversa)
        #return $this->hasOne(Becado::class, 'becado_id');

    	return $this->belongsTo(Becado::class, 'becado_id');
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
            $query->has('becado');
            // internamente se aplica el filtro que tiene definido el modelo Becado, con lo que se excluyen
            // todos los calendarios que pertenecen a un becado deshabilitado.
        });

    }
}
