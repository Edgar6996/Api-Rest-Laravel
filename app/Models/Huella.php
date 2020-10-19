<?php

namespace App\Models;

use App\Models\Becado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Huella
 * @package App\Models
 * @mixin  \Eloquent
 */
class Huella extends Model
{
    use HasFactory;

    protected $table = 'huellas';

    # Definimos los siguientes mutators para codificar los datos binarios en base64,
    # ya que no es posible enviar binario por json

    public function getImgHuellaAttribute($value)
    {
        return base64_encode($this->attributes['img_huella']);
    }
    public function getTemplateHuellaAttribute($value)
    {
        return base64_encode($this->attributes['template_huella']);
    }

    public function becado(){
    	return $this->hasOne(Becado::class, 'becado_id');
    }
}
