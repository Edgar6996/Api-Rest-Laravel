<?php

namespace App\Models;

use App\Models\Becado;
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
    	return $this->hasOne(Becado::class, 'becado_id');
    }
}
