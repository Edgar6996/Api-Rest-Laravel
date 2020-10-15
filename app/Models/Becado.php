<?php

namespace App\Models;

use App\Models\Calendario;
use App\Models\DetalleDiario;
use App\Models\Huella;
use App\Models\Registro;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Becado extends Model
{
    use HasFactory;

    #nombre de la tabla que hace referencia
    protected $table = 'becados';

    #relaciones

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


}
