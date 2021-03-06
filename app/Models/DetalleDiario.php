<?php

namespace App\Models;

use App\Models\Becado;
use App\Models\Diario;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DetalleDiario
 * @package App\Models
 * @mixin  \Eloquent
 */
class DetalleDiario extends Model
{
    use HasFactory;

    protected $table = 'detalle_diarios';

    protected $fillable = [ 'diario_id', 'becado_id', 'raciones'];

    public function becado(){

    	return $this->belongsTo(Becado::class,'becado_id');
    }

    public function diario(){
    	return $this->belongsTo(Diario::class, 'diario_id');
    }
}
