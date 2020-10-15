<?php

namespace App\Models;

use App\Models\Becado;
use App\Models\Diario;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleDiario extends Model
{
    use HasFactory;

    protected $table = 'detallediarios';

    public function becado(){

    	return $this->hasOne(Becado::class,'becado_id');
    }

    public function diario(){
    	return $this->belongsTo(Diario::class, 'diario_id');
    }
}
