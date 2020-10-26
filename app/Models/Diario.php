<?php

namespace App\Models;

use App\Models\DetalleDiario;
use App\Models\Diario;
use App\Models\Registro;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diario extends Model
{
    use HasFactory;

    protected $table = 'diarios';

    protected $dates = [
        'fecha',
    ];

    protected $fillable  = [
       'fecha', 'horario_comida','total_raciones',
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


}
