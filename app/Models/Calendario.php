<?php

namespace App\Models;

use App\Models\Becado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calendario extends Model
{
    use HasFactory;

    #nombre de la tabla que hace referencia
    protected $table = 'calendarios';

    public function becado(){
    	return $this->hasOne(Becado::class, 'becado_id');
    }
}
