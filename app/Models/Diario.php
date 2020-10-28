<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Diario
 *
 * @package App\Models
 * @mixin \Eloquent
 *
 * @property string horario_comida
 * @property int total_raciones
 * @property Carbon fecha
 */
class Diario extends Model
{
    use HasFactory;

    protected $table = 'diarios';

    protected $dates = ['fecha'];

    protected $fillable  = [
       'fecha', 'horario_comida','total_raciones',
    ];

    protected $casts = [
        'total_raciones' => 'integer'
    ];

    #Relaciones
    public function registro(){
        return $this->belongsTo(Registro::class, 'registro_id');
    }

    public function detalleDiario(){
    	return $this->hasMany(DetalleDiario::class, 'diario_id');
    }

    public static function diarioActual(){
       return Diario::orderBy('fecha', 'DESC')->first();
    }

    public function calcularRacionesDisponibles(){
        $retirado = $this->detalleDiario()->where('retirado',1)->sum('raciones');

        return $this->total_raciones-$retirado;
    }

    public function actualizarTotalRaciones(){
        $total = $this->detalleDiario()->sum('raciones');

        $this->total_raciones = $total;

        $this->save();
    }
}
