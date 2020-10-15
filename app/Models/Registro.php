<?php

namespace App\Models;

use App\Models\Becado;
use App\Models\Diario;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
    use HasFactory;

    #nombre de la tabla que hace referencia
    protected $table = 'registros';

    #relacion
    public function becado(){
        return $this->belongsTo(Becado::class, 'becado_id');
    }

    public function diario(){
    	return $this->hasMany(Diario::class, 'registro_id');
    }


}
