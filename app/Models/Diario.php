<?php

namespace App\Models;

use App\Core\Services\DiariosService;
use App\Models\AppConfig;
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
       'fecha', 'horario_comida', 'menu_comida','total_raciones',
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
       $diarioActual = Diario::orderBy('fecha', 'DESC')->first();
    //    if (!$diarioActual) {
    //         $servicio = new DiariosService();
    //         $servicio->generarProximoDiario();
    //    }

       return $diarioActual;
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

    public function horaLimite(){

        $hs_config = new AppConfig;
        $diario_actual = Diario::diarioActual();

        $hora_comida = $diario_actual->fecha->hour;

        $hora = $hora_comida - $hs_config->value('limite_horas_cancelar_reserva');

        $hora_limite = Carbon::now()->setTime($hora,0,0);

        return $hora_limite;
    }
}
